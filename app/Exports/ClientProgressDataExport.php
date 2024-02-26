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
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use App\Helpers\Helper;

class ClientProgressDataExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping, WithColumnFormatting
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    protected $business_id;

    function __construct($from_date,$to_date,$business_id,$candidate_id,$service_id) {
        $this->from_date   = $from_date;
        $this->to_date   = $to_date;
        $this->business_id   = $business_id;
        $this->candidate_id = $candidate_id;
        $this->service_id = $service_id;
    }

    public function collection()
    {
        // $user=[]; 

        $query = DB::table('candidate_reinitiates as u')
                    ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','r.status as report_status','r.report_complete_created_at as case_completed_date','j.price_type','j.package_price','r.is_report_complete','r.id as report_id','r.is_manual_mark')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->join('reports as r','r.candidate_id','=','u.id')
                    ->whereIn('u.id',$this->candidate_id);
                   
                // $query->orderBy('u.id','desc');

        $user = $query->orderBy('u.id','desc')->get();  
        
        // dd($user);

        return $user;

    }

    public function map($user): array
    {

        $candidate_date = date('Y-m-d',strtotime($user->created_at));
        
        $data = [
                    //$user->company_name,
                    $user->name,
                    $user->client_emp_code!=NULL ? $user->client_emp_code : 'N/A',
                    $user->sla_name!=NULL ? $user->sla_name : 'N/A',

                ];
        
        // Ref no, Case Initiated Date

        array_push($data,
                        $user->display_id!=NULL ? $user->display_id : 'N/A',
                        date('d-M-y',strtotime($candidate_date))
        );

        // Case Due Date

        $date_arr = [];
        $tat = $user->client_tat - 1;
        $client_tat = $user->client_tat - 1;
        $tat_date = 'N/A';
        $client_tat_date = 'N/A';

        if(stripos($user->days_type,'working')!==false)
        {
            $date_arr = $this->workingDays($candidate_date,$tat,$client_tat);

            // $tat_date = $date_arr['tat_date'];

            $client_tat_date = $date_arr['inc_tat_date'];
        }
        else if(stripos($user->days_type,'calender')!==false)
        {
            $holiday_master=DB::table('customer_holiday_masters')
                                ->distinct('date')
                                ->select('date')
                                ->where(['business_id'=>$user->parent_id,'status'=>1])
                                ->orderBy('date','asc')
                                ->get();

            if(count($holiday_master)>0)
            {
                $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$client_tat);
            }
            else
            {
                $date_arr = $this->workingDays($candidate_date,$tat,$client_tat);
            }

            // $tat_date = $date_arr['tat_date'];

            $client_tat_date = $date_arr['inc_tat_date'];
        }

        array_push($data,$client_tat_date);

        // Report Shared Date

        array_push($data,$user->is_report_complete==1 && $user->case_completed_date!=NULL ? date('d-M-y',strtotime($user->case_completed_date)) : 'N/A');

        // Status & Today

        $candidate_hold = DB::table('candidate_hold_statuses')
                            ->where(['candidate_id'=>$user->id])
                            ->whereNotNull('hold_by')
                            ->whereNull('hold_remove_by')
                            ->latest()
                            ->first();
        $status = 'WIP';
        
        if($candidate_hold!=NULL)
        {
            $status = 'STOP';
        }
        else if(stripos($user->report_status,'completed')!==false)
        {
            $status = 'Complete';
        }

        $color_name = '';

        if($user->is_manual_mark==1)
        {
            $color_name = 'Green';
        }
        else if($user->is_manual_mark==2)
        {
            $color_name = 'Grey';
        }
        else
        {
            $color_name = Helper::get_approval_status_color_name($user->report_id);
        }
        
        array_push($data,$status,$color_name!=''?$color_name:'N/A');

        // dd($this->service_id);

        foreach($this->service_id as $service_id)
        {
            $no_of_verification=1;
            $max_verification=1;

            $max_verification=DB::table('report_items as ri')
                            ->select('ri.service_item_number')
                            ->where('ri.service_id',$service_id)
                            ->whereIn('ri.candidate_id',$this->candidate_id)
                            ->max('ri.service_item_number');
            //dd($max_verification);
            $report_items=DB::table('report_items')
                        ->where(['candidate_id'=>$user->id,'service_id'=>$service_id])
                        ->orderBy('service_item_number','asc')
                        ->get();

            $no_of_verification=count($report_items);

            $services=DB::table('services')->where('id',$service_id)->first();

            // Check Label Name

            if(stripos($services->type_name,'educational')!==false)
            {
                array_push($data,'Education');
            }
            else
            {
                array_push($data,ucwords($services->name));
            }

            // Details & Remarks

            if(count($report_items)>0)
            {
                if($no_of_verification == $max_verification)
                {
                    foreach($report_items as $item)
                    {
                        $details = '';

                        $remarks = 'WIP';

                        if($item->is_data_verified==1)
                            $remarks = 'Completed';

                        $jaf_data = $item->jaf_data;

                        if($jaf_data!=NULL)
                        {
                            $jaf_data_arr = [];

                            $jaf_data_arr=json_decode($jaf_data,true);

                            foreach($jaf_data_arr as $k => $input)
                            {
                                $key_val = array_keys($input); $input_val = array_values($input);

                                if(stripos($services->type_name,'address')!==false)
                                {
                                    if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Pincode')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'aadhar')!==false)
                                {
                                    if(stripos($key_val[0],'Aadhar Number')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'pan')!==false)
                                {
                                    if(stripos($key_val[0],'PAN Number')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'voter_id')!==false)
                                {
                                    if(stripos($key_val[0],'Voter ID Number')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'rc')!==false)
                                {
                                    if(stripos($key_val[0],'RC Number')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'passport')!==false)
                                {
                                    if(stripos($key_val[0],'File Number')!==false || stripos($key_val[0],'Date of Birth')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'driving_license')!==false)
                                {
                                    if(stripos($key_val[0],'DL Number')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'employment')!==false)
                                {
                                    if(stripos($key_val[0],'Company name')!==false)
                                    {
                                        $details.=$input_val[0];
                                    }  
                                }
                                elseif(stripos($services->type_name,'educational')!==false)
                                {
                                    if(stripos($key_val[0],'University Name / Board Name')!==false)
                                    {
                                        $details.=$input_val[0];
                                    }  
                                }
                                elseif(stripos($services->type_name,'bank_verification')!==false)
                                {
                                    if(stripos($key_val[0],'Account Number')!==false || stripos($key_val[0],'IFSC Code')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'criminal')!==false || stripos($services->type_name,'judicial')!==false)
                                {
                                    if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Address Type')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'reference')!==false)
                                {
                                    if(stripos($key_val[0],'Referee Name')!==false || stripos($key_val[0],'Referee Designation')!==false || stripos($key_val[0],'Referee Company')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }
                                }
                                elseif(stripos($services->type_name,'database')!==false)
                                {
                                    if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Type')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'telecom')!==false)
                                {
                                    if(stripos($key_val[0],'Telephone Number')!==false || stripos($key_val[0],'Addesss')!==false)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                    }  
                                }
                                elseif(stripos($services->type_name,'covid_19')!==false)
                                {
                                    
                                    $details.=$key_val[0].': '.$input_val[0].', ';
                                      
                                }
                                elseif(stripos($services->type_name,'covid_19_certificate')!==false)
                                {
                                    if(stripos($key_val[0],'Mobile Number')!==false || stripos($key_val[0],'Reference ID')!==false)
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                      
                                }
                                elseif(stripos($services->type_name,'e_court')!==false || stripos($services->type_name,'upi')!==false || stripos($services->type_name,'cin')!==false)
                                {
                                    
                                    $details.=$key_val[0].': '.$input_val[0].', ';
                                      
                                }
                                elseif(stripos($services->type_name,'drug_test_5')!==false || stripos($services->type_name,'drug_test_6')!==false || stripos($services->type_name,'drug_test_7')!==false || stripos($services->type_name,'drug_test_8')!==false || stripos($services->type_name,'drug_test_9')!==false || stripos($services->type_name,'drug_test_10')!==false)
                                {
                                    if(stripos($key_val[0],'Test Name')!==false)
                                    {
                                        $drug_test_name = Helper::drugTestName($services->id);
                                        if(count($drug_test_name)>0)
                                        {
                                            $arr = $drug_test_name->pluck('test_name')->all();
                                            $value = implode(', ',$arr);
                                            $details.=$key_val[0].': ('.$value.'), ';
                                        }
                                        else
                                            $details.=$key_val[0].': '.$input_val[0].', ';
                                    }
                                    else
                                        $details.=$key_val[0].': '.$input_val[0].', ';
                                }
                                elseif(stripos($services->type_name,'cibil_new')!==false)
                                {
                                    $details.=$key_val[0].': '.$input_val[0].', ';
                                }
                                else
                                {
                                    if($k==0)
                                    {
                                        $details.=$key_val[0].': '.$input_val[0];
                                    }
                                }
                            }
                        }

                        array_push($data,$details!=''?$details:'N/A',$remarks);
                    }
                }
                else
                {
                    for($i=0; $i < $max_verification; $i++)
                    {
                        if($i<$no_of_verification)
                        {
                            $j=$i+1;

                            $report_items=DB::table('report_items')
                                            ->where(['candidate_id'=>$user->id,'service_id'=>$service_id,'service_item_number'=>$j])
                                            ->orderBy('service_item_number','asc')
                                            ->get();

                            foreach($report_items as $item)
                            {
                                $details = '';

                                $remarks = 'WIP';

                                if($item->is_data_verified==1)
                                    $remarks = 'Completed';

                                $jaf_data = $item->jaf_data;

                                if($jaf_data!=NULL)
                                {
                                    $jaf_data_arr = [];

                                    $jaf_data_arr=json_decode($jaf_data,true);

                                    foreach($jaf_data_arr as $k => $input)
                                    {
                                        $key_val = array_keys($input); $input_val = array_values($input);

                                        if(stripos($services->type_name,'address')!==false)
                                        {
                                            if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Pincode')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'aadhar')!==false)
                                        {
                                            if(stripos($key_val[0],'Aadhar Number')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'pan')!==false)
                                        {
                                            if(stripos($key_val[0],'PAN Number')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'voter_id')!==false)
                                        {
                                            if(stripos($key_val[0],'Voter ID Number')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'rc')!==false)
                                        {
                                            if(stripos($key_val[0],'RC Number')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'passport')!==false)
                                        {
                                            if(stripos($key_val[0],'File Number')!==false || stripos($key_val[0],'Date of Birth')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'driving_license')!==false)
                                        {
                                            if(stripos($key_val[0],'DL Number')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'employment')!==false)
                                        {
                                            if(stripos($key_val[0],'Company name')!==false)
                                            {
                                                $details.=$input_val[0];
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'educational')!==false)
                                        {
                                            if(stripos($key_val[0],'University Name / Board Name')!==false)
                                            {
                                                $details.=$input_val[0];
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'bank_verification')!==false)
                                        {
                                            if(stripos($key_val[0],'Account Number')!==false || stripos($key_val[0],'IFSC Code')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'criminal')!==false || stripos($services->type_name,'judicial')!==false)
                                        {
                                            if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Address Type')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'reference')!==false)
                                        {
                                            if(stripos($key_val[0],'Referee Name')!==false || stripos($key_val[0],'Referee Designation')!==false || stripos($key_val[0],'Referee Company')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }
                                        }
                                        elseif(stripos($services->type_name,'database')!==false)
                                        {
                                            if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Type')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'telecom')!==false)
                                        {
                                            if(stripos($key_val[0],'Telephone Number')!==false || stripos($key_val[0],'Addesss')!==false)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            }  
                                        }
                                        elseif(stripos($services->type_name,'covid_19')!==false)
                                        {
                                            
                                            $details.=$key_val[0].': '.$input_val[0].', ';
                                            
                                        }
                                        elseif(stripos($services->type_name,'covid_19_certificate')!==false)
                                        {
                                            if(stripos($key_val[0],'Mobile Number')!==false || stripos($key_val[0],'Reference ID')!==false)
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                            
                                        }
                                        elseif(stripos($services->type_name,'e_court')!==false || stripos($services->type_name,'upi')!==false || stripos($services->type_name,'cin')!==false)
                                        {
                                            
                                            $details.=$key_val[0].': '.$input_val[0].', ';
                                            
                                        }
                                        elseif(stripos($services->type_name,'drug_test_5')!==false || stripos($services->type_name,'drug_test_6')!==false || stripos($services->type_name,'drug_test_7')!==false || stripos($services->type_name,'drug_test_8')!==false || stripos($services->type_name,'drug_test_9')!==false || stripos($services->type_name,'drug_test_10')!==false)
                                        {
                                            if(stripos($key_val[0],'Test Name')!==false)
                                            {
                                                $drug_test_name = Helper::drugTestName($services->id);
                                                if(count($drug_test_name)>0)
                                                {
                                                    $arr = $drug_test_name->pluck('test_name')->all();
                                                    $value = implode(', ',$arr);
                                                    $details.=$key_val[0].': ('.$value.'), ';
                                                }
                                                else
                                                    $details.=$key_val[0].': '.$input_val[0].', ';
                                            }
                                            else
                                                $details.=$key_val[0].': '.$input_val[0].', ';
                                        }
                                        elseif(stripos($services->type_name,'cibil_new')!==false)
                                        {
                                            $details.=$key_val[0].': '.$input_val[0].', ';
                                        }
                                        else
                                        {
                                            if($k==0)
                                            {
                                                $details.=$key_val[0].': '.$input_val[0];
                                            }
                                        }
                                    }
                                }

                                array_push($data,$details!=''?$details:'N/A',$remarks);
                            }
                        }
                        else
                        {
                            array_push($data,'N/A','N/A');
                        }
                    }
                }
            }
            else
            {
                for($i=0; $i < $max_verification; $i++)
                {
                    array_push($data,'N/A','N/A');
                }
                
            }

        }

        // dd($data);

        return $data;

    }

    public function headings(): array
    {
        $columns=[
            ['Date'],
            ['Client Code'],
            [
                    // 'Client',
                    'Candidate',
                    'EmployeeCode',
                    'SLA',	
                    'ReferenceNo.',	
                    'Case Initiated Date',
                    'Case Due Date',
                    'Report Shared Date',
                    'Case Status',
                    'Report Color Code',
                    // 'Today',
                    // 'EDUCATION',
                    // 'University',
                    // 'Status',
                    // 'EMPLOYMENT',
                    // 'Employer Name',
                    // 'Status',
                    // 'REFERENCE',
                    // 'Reference Name',
                    // 'Reference Contact-No',
                    // 'Status',
                    // 'ADDRESS',
                    // 'Details',
                    // 'Status',
                    // 'JUDIS',
                    // 'Details',
                    // 'Status',
                    // 'IDENTITY',
                    // 'Details',
                    // 'Status',
                    // 'DATABASE',
                    // 'Details',
            ],
        ];
        // $columns = [
        //             'Client',
        //             'Candidate',
        //             'EmployeeCode',
        //             'SLA',	
        //             'ReferenceNo.',	
        //             'Case Initiated Date',
        //             'Case Due Date',
        //             'Report Shared Date',
        //             'Case Status',
        //             'Report Color Code',
        //             // 'Today',
        //             // 'EDUCATION',
        //             // 'University',
        //             // 'Status',
        //             // 'EMPLOYMENT',
        //             // 'Employer Name',
        //             // 'Status',
        //             // 'REFERENCE',
        //             // 'Reference Name',
        //             // 'Reference Contact-No',
        //             // 'Status',
        //             // 'ADDRESS',
        //             // 'Details',
        //             // 'Status',
        //             // 'JUDIS',
        //             // 'Details',
        //             // 'Status',
        //             // 'IDENTITY',
        //             // 'Details',
        //             // 'Status',
        //             // 'DATABASE',
        //             // 'Details',

        // ];
        
        foreach($this->service_id as $service_id)
        {
            $report_items=DB::table('report_items as ri')
                            ->select('ri.*')
                            ->where('ri.service_id',$service_id)
                            ->whereIn('ri.candidate_id',$this->candidate_id)
                            ->get();

            $no_of_verification=[];

            if(count($report_items)>0)
            {
                $max=1;

                foreach($report_items as $item)
                {
                    $no_of_verification[]=$item->service_item_number;
                }

                $max=max($no_of_verification);

                $service = DB::table('services')->where('id',$service_id)->first();

                $check_name = ucwords($service->name);

                // if(stripos($service->name,'educational')!==false)
                // {
                //     array_push($columns[2],'Education');
                // }
                // else
                // {
                //     array_push($columns[2],$check_name);
                // }

                array_push($columns[2],'Check Type');

                for($i = 0; $i < $max; $i++)
                {
                    $j=$i+1;

                    $detail = '';

                    if(stripos($service->type_name,'educational')!==false)
                    {
                        $detail = 'University/ Colleage / Institute Name - '.$j;
                    }
                    else if(stripos($service->type_name,'employment')!==false)
                    {
                        $detail = 'Employer Name - '.$j;
                    }
                    else
                    {
                        $detail = 'Details - '.$j;
                    }

                    array_push($columns[2],$detail,'Remarks');
                }
            }
        }
        //dd($columns);
        return $columns;
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A3:ZZ3'; // All headers

                $query = DB::table('candidate_reinitiates as u')
                            ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','r.status as report_status','r.report_complete_created_at as case_completed_date','j.price_type','j.package_price','r.is_report_complete')
                            ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->join('reports as r','r.candidate_id','=','u.id')
                            ->whereIn('u.id',$this->candidate_id);
               
                        // $query->orderBy('u.id','desc');

                    $user = $query->orderBy('u.id','desc')->get();  

                    $q_count = count($user);

                // $event->sheet->getDelegate()->getStyle('A1:AG1')
                //                             ->getAlignment()
                //                             ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                //                             ->setVertical(Alignment::VERTICAL_CENTER);

                // $event->sheet->getDelegate()->getStyle('A1:K1')->getFill()
                //                         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //                         ->getStartColor()
                //                         ->setRGB('6FCBDB');

                // $event->sheet->getDelegate()->getStyle('A1:K1')
                //                             ->getFont()
                //                             ->getColor()
                //                             ->setRGB('000000');
                // $i=65;
                // $j=90;
                // $status = 0;

                // while($i<=$j)
                // {
                //     $cell = chr($i)."1";

                //     if($status == 1)
                //     {
                //         $cell = 'A'.chr($i)."1";
                //     }

                //     $event->sheet->getDelegate()->getStyle($cell)->applyFromArray([
                //         'borders' => [
                //             'outline' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //                 'color' => ['argb' => '00000000'],
                //             ],
                //         ],
                //     ]);

                //     $i++;

                //     if($cell=='Z1')
                //     {
                //         $i=65;
                //         $j=71;

                //         $status = 1;
                //     }

                    

                //     // $event->sheet->getDelegate()->getStyle('A2:FX'.($q_count + 1))
                //     //                             ->getAlignment()
                //     //                             ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                //     //                             ->setVertical(Alignment::VERTICAL_CENTER); 
                    
                // }

                // Date & Client Code

                $event->sheet->setCellValue('B1',strval(date('d-M-y')));

                $event->sheet->setCellValue('B2',strval(Helper::user_reference_id($this->business_id)));

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);

                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(30);

                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(30);

                $event->sheet->getDelegate()->getStyle('A1:B1')->getFont()->setSize(12);

                $event->sheet->getDelegate()->getStyle('A1:B1')->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('A1:B1')
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:B2')->getFont()->setSize(12);

                $event->sheet->getDelegate()->getStyle('A2:B2')->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('A2:B2')
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle($cellRange)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                //$event->sheet->getDelegate()->getStyle('AG2:AG'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            },
        ];

        
    }

    public function columnFormats(): array
    {
        return [
            // 'AG' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];

        $tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        $arr=['tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date];

        return $arr;
        
    }

    public function calenderDays($start_date,$holidays,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        // $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        foreach($holidays as $holiday)
        {
        
            $holiday_ts = strtotime($holiday->date);

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $tat_new_date = date('Y-m-d', strtotime("{$tat_new_date} + 1 weekdays"));
                }
            }

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($inc_tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $inc_tat_new_date = date('Y-m-d', strtotime("{$inc_tat_new_date} + 1 weekdays"));
                }
            }
        }

        return array('tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date);
    }
    
    public function round_up ( $value, $precision ) { 
        $pow = pow ( 10, $precision ); 
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
    }
}