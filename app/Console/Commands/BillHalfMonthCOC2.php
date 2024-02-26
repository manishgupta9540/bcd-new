<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
class BillHalfMonthCOC2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billhalfmonthcoc2:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a monthly record for COC on last day of month to calculate price';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function customers_list()
    {
        $customers=DB::table('users')->where('user_type','customer')->get();
            
        $array_result=[];
        foreach($customers as $cust)
        {
            $business_id = $cust->business_id;
            $items = DB::table('users as u')
            ->select('u.id')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['u.user_type'=>'client','u.parent_id'=>$business_id,'b.billing_cycle_period'=>'half_monthly'])
            ->whereNotIn('u.id',[$business_id])->get();

            if(count($items)>0)
            {
                foreach($items as $item){
                    $array_result[]=$item->id;
                }
            }   
        }
        // dd($array_result);
        return $array_result;
    }

    public function candidates_list(){
        $array_result=[];
        $customers=$this->customers_list();
        // dd($customers);
        if(count($customers)>0)
        {
            foreach($customers as $cust_id){
                $candidates = DB::table('candidate_reinitiates as u')
                ->select('r.business_id','r.candidate_id','r.status','r.id as report_id','r.parent_id','r.sla_id')
                ->join('reports as r','r.candidate_id','=','u.id')
                ->where(['u.user_type'=>'candidate','r.business_id'=>$cust_id])
                ->whereIn('r.status',['completed'])
                ->get();
                if(count($candidates)>0){
                    foreach($candidates as $item){
                        // if($item->status=='completed')
                        $array_result[]=['report_id'=>$item->report_id,'parent_id'=>$item->parent_id,'business_id'=>$cust_id,'candidate_id'=>$item->candidate_id,'sla_id'=>$item->sla_id];
                    }
                }
            }
        }
        // dd($array_result);
        return $array_result;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $report_list=$this->candidates_list();
        $customers=$this->customers_list();
        $array_result=[];

        $today_date=date('Y-m-t');

        $start_date=date('Y-m-16');

        // dd($start_date);

        if(count($report_list)>0)
        {
            foreach($report_list as $items){

                $report_items = DB::table('report_items as ri')
                    ->select('ri.report_id as report_id','s.name as service_name','s.id as service_id','ri.service_item_number as service_no','r.created_at','r.parent_id','u.created_at as candidate_creation_date','u.id as candidate_id','r.complete_created_at as completed_date','j.tat_type','j.days_type','j.id as job_item_id','r.status as report_status','r.generated_at','j.incentive as case_incentive','j.incentive as case_penalty','j.client_tat','j.price_type','j.package_price','ri.data_verified_date','ri.jaf_id','ri.additional_charges','ri.additional_charge_notes','ri.is_supplementary','ri.is_charge_allowed')  
                    ->join('reports as r','r.id','=','ri.report_id')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    // ->join('check_prices as c','c.service_id','=','s.id')
                    ->where(['ri.report_id'=>$items['report_id'],'ri.is_data_verified'=>'1']) 
                    ->whereDate('ri.data_verified_date','>=',$start_date)
                    ->whereDate('ri.data_verified_date','<=',$today_date)
                    ->orderBy('r.business_id','asc')
                    ->get();
                    if(count($report_items)>0){   
                        foreach($report_items as $item){
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));
                            // $completed_date = date('Y-m-d',strtotime($item->completed_date));
    
                            // if(stripos($item->report_status,'interim')!==false)
                            //     $completed_date = date('Y-m-d',strtotime($item->generated_at));
                            // elseif(stripos($item->report_status,'completed')!==false)
                            //     $completed_date = date('Y-m-d',strtotime($item->completed_date));
                            // else
                            //     $completed_date = date('Y-m-d',strtotime($item->generated_at));
    
                            $completed_date = date('Y-m-d',strtotime($item->data_verified_date));
    
                            // $price=20.00;
                            // $data = DB::table('check_price_cocs')->where(['service_id'=>$item->service_id,'coc_id'=>$items['business_id']])->first();
                            // if($data!=NULL)
                            // {
                            //     $price=$data->price;
                            // }
                            // else{
                            //     $data=DB::table('check_prices')->where(['service_id'=>$item->service_id,'business_id'=>$items['parent_id']])->first();
                            //     if($data!=NULL)
                            //     {
                            //         $price=$data->price;
                            //     }
                            //     // else{
                            //     //     $data=DB::table('check_price_masters')->where(['business_id'=>$item->parent_id,'service_id'=>$item->service_id])->first();
                            //     //     if($data!=NULL)
                            //     //     {
                            //     //         $price=$data->price;
                            //     //     }
                            //     // }
                            // }
    
                            $price = 20;
                            $price_type = $item->price_type;
    
                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr=[];
                                $incentive=0;
                                $penalty=0;
    
                                $job_sla_items=DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id])->first();
    
                                if(stripos($price_type,'check')!==false)
                                {
                                    $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();
    
                                    if($job_sla_i!=NULL)
                                    {
                                        $price = $job_sla_i->price;
                                    }
                                    else
                                    {
                                        $price = $job_sla_items->price;
                                    }
                                }
                                else if(stripos($price_type,'package')!==false)
                                {
                                    if($item->is_supplementary==1)
                                    {
                                        $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();
    
                                        if($job_sla_i!=NULL)
                                        {
                                            $price = $job_sla_i->price;
                                        }
                                        else
                                        {
                                            $price = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        $report_item_data = DB::table('report_items')->where('report_id',$item->report_id)->get();
                                        $count = count($report_item_data);
                                        $price = $this->round_up($item->package_price/$count,2);
                                    }
                                }
                                
                                $tat = $item->client_tat - 1;
                                $incentive_tat = $item->client_tat - 1;
    
                                // check if its a additional check
                                if($item->is_supplementary==1)
                                {
                                    $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();
    
                                    if($job_sla_i!=NULL)
                                    {
                                        $tat = $job_sla_i->tat - 1;
                                        $incentive_tat = $job_sla_i->incentive_tat - 1;
                                    }
                                }
    
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                    }
                                }
    
                                if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                                {
                                    $incentive = $item->case_incentive;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $penalty = $item->case_penalty;
                                }

                                $additional_charge = 0.00;

                                $additional_charge_notes = NULL;

                                if($item->is_charge_allowed==1)
                                {
                                    $additional_charge = $item->additional_charges;

                                    $additional_charge_notes = $item->additional_charge_notes;
                                }
    
                                $array_result[]=['parent_id'=>$items['parent_id'],'business_id'=>$items['business_id'],'candidate_id'=>$items['candidate_id'],'report_id'=>$item->report_id,'report_status'=>$item->report_status,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_item_no'=>$item->service_no,'price'=>$price,'price_type'=>$price_type,'incentive'=>$incentive,'penalty'=>$penalty,'start_date'=>$start_date,'end_date'=>$today_date,'tat_date'=>$date_arr['tat_date'],'inc_tat_date'=>$date_arr['inc_tat_date'],'jaf_id'=>$item->jaf_id,'is_charge_allowed'=>$item->is_charge_allowed,'additional_charge'=>$additional_charge,'additional_charge_notes'=>$additional_charge_notes];
                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                $job_sla_items=DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id])->first();
    
                                if($job_sla_items!=NULL)
                                {
                                    $date_arr=[];
                                    $tat=$job_sla_items->tat - 1;
                                    $incentive_tat = $job_sla_items->incentive_tat - 1;
                                    $incentive=0;
                                    $penalty=0;
    
                                    // check if its a additional check
                                    if($item->is_supplementary==1)
                                    {
                                        $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();
    
                                        if($job_sla_i!=NULL)
                                        {
                                            $tat = $job_sla_i->tat - 1;
                                            $incentive_tat = $job_sla_i->incentive_tat - 1;
                                        }
                                    }
    
                                    if(stripos($price_type,'check')!==false)
                                    {
                                        $price = $job_sla_items->price;
                                    }
                                    else if(stripos($price_type,'package')!==false)
                                    {
                                        if($item->is_supplementary==1)
                                        {
                                            $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();
    
                                            if($job_sla_i!=NULL)
                                            {
                                                $price = $job_sla_i->price;
                                            }
                                            else
                                            {
                                                $price = $job_sla_items->price;
                                            }
                                        }
                                        else
                                        {
                                            $report_item_data = DB::table('report_items')->where('report_id',$item->report_id)->get();
                                            $count = count($report_item_data);
                                            $price = $this->round_up($item->package_price/$count,2);
                                        }
                                    }
    
                                    if(stripos($item->days_type,'working')!==false)
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                    }
                                    else if(stripos($item->days_type,'calender')!==false)
                                    {
                                        $holiday_master=DB::table('customer_holiday_masters')
                                                        ->distinct('date')
                                                        ->select('date')
                                                        ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                        ->orderBy('date','asc')
                                                        ->get();
                                        if(count($holiday_master)>0)
                                        {
                                            $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                        }
                                        else
                                        {
                                            $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                        }
    
                                    }
    
                                    $coc_incentive=DB::table('check_coc_incentives')->where(['coc_id'=>$job_sla_items->business_id,'service_id'=>$job_sla_items->service_id])->first();
    
    
                                    if($coc_incentive!=NULL)
                                    {
                                        // dd($date_arr);
                                        //check if task completed date is less than or equal to incentive Date
                                        if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                                        {
                                            $incentive = $coc_incentive->incentive;
                                        }
                                        else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $penalty = $coc_incentive->penalty;
                                        }
                                    }

                                    $additional_charge = 0.00;

                                    $additional_charge_notes = NULL;

                                    if($item->is_charge_allowed==1)
                                    {
                                        $additional_charge = $item->additional_charges;

                                        $additional_charge_notes = $item->additional_charge_notes;
                                    }
    
                                    $array_result[]=['parent_id'=>$items['parent_id'],'business_id'=>$items['business_id'],'candidate_id'=>$items['candidate_id'],'report_id'=>$item->report_id,'report_status'=>$item->report_status,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_item_no'=>$item->service_no,'price'=>$price,'price_type'=>$price_type,'incentive'=>$incentive,'penalty'=>$penalty,'start_date'=>$start_date,'end_date'=>$today_date,'tat_date'=>$date_arr['tat_date'],'inc_tat_date'=>$date_arr['inc_tat_date'],'jaf_id'=>$item->jaf_id,'is_charge_allowed'=>$item->is_charge_allowed,'additional_charge'=>$additional_charge,'additional_charge_notes'=>$additional_charge_notes];
                                }
    
                            }
                        }
                    }
                    // $array_result[]=$report_items;
            }
        }

        // dd($array_result);

        // insertion in billing db from report 
        if(count($array_result)>0)
        {
            foreach($array_result as $record)
            {
                $invoice_no = strtoupper(substr('BCD', 0, 3)).date("-ymds");

                $billing_r=DB::table('billings')
                    ->where(['business_id'=>$record['business_id']])
                    ->whereDate('start_date','=',$start_date)
                    ->whereDate('end_date','=',$today_date)
                    ->first();
                if($billing_r==NULL)
                {
                    $billing_data=[
                        'parent_id'   => $record['parent_id'],
                        'business_id' => $record['business_id'],
                        'user_id'     => $record['business_id'],
                        // 'invoice_id'  => 'invoice_no'.Str::random(10),
                        'start_date'  => date('Y-m-d H:i:s',strtotime($start_date)),
                        'end_date'  => date('Y-m-d H:i:s',strtotime($today_date)),
                        'created_at'=> date('Y-m-d H:i:s'),
                    ];
                    $bill=DB::table('billings')->insertGetId($billing_data);

                    DB::table('billings')->where(['id'=>$bill])->update([
                        'invoice_id' => $invoice_no.$bill
                    ]);

                    $billing_item_data=[
                    'billing_id'  => $bill,
                    'parent_id'   => $record['parent_id'],
                    'business_id' => $record['business_id'],
                    'user_id'     => $record['business_id'],
                    'candidate_id'=> $record['candidate_id'],
                    'service_id'  => $record['service_id'],
                    'service_name'=> $record['service_name'],
                    'service_item_number' =>$record['service_item_no'],
                    'quantity'    => 1,
                    'price'       =>$record['price'],
                    'incentive'   =>$record['incentive'],
                    'penalty'     =>$record['penalty'],
                    'tat_date'    => $record['tat_date'],
                    'incentive_tat_date'    => $record['inc_tat_date'],
                    'report_status'     => $record['report_status'],
                    'additional_charges' => $record['additional_charge'],
                    'additional_charge_notes' => $record['additional_charge_notes'],
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s')
                    ];
                    $bill_items=DB::table('billing_items')->insertGetId($billing_item_data);

                    $jaf_add_attachment = DB::table('jaf_additional_charge_attachments')->where(['jaf_id'=>$record['jaf_id']])->get();

                    if(count($jaf_add_attachment)>0 && $record['is_charge_allowed']==1)
                    {
                        foreach($jaf_add_attachment as $attach)
                        {
                            DB::table('billing_additional_charge_attachments')->where(['billing_id'=>$bill,'service_id'=>$attach->service_id,'service_item_number'=>$attach->service_item_number])->delete();

                            $jaf_file_path = public_path().'/uploads/jaf/additional-charge/';

                            $bill_file_path = public_path().'/uploads/billings/additional-charge/';
                            
                            if(File::exists($bill_file_path.$attach->file_name))
                            {
                                File::delete($bill_file_path.$attach->file_name);
                            }

                            if(File::exists($jaf_file_path.$attach->file_name))
                            {
                                File::copy($jaf_file_path.$attach->file_name,$bill_file_path.$attach->file_name);
                            }

                            DB::table('billing_additional_charge_attachments')->insert([
                                'billing_id' => $bill,
                                'billing_details_id' => $bill_items,
                                'business_id' => $attach->business_id,
                                'service_id' => $attach->service_id,
                                'service_item_number' => $attach->service_item_number,
                                'file_name' => $attach->file_name,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
                else{
                    DB::table('billing_items')->where(['billing_id'=>$billing_r->id,'candidate_id'=>$record['candidate_id'],'service_id'=>$record['service_id'],'service_item_number'=>$record['service_item_no']])->delete();
                    $billing_item_data1=[
                        'billing_id'  => $billing_r->id,
                        'parent_id'   => $record['parent_id'],
                        'business_id' => $record['business_id'],
                        'user_id'     => $record['business_id'],
                        'candidate_id'=> $record['candidate_id'],
                        'service_id'  => $record['service_id'],
                        'service_name'=> $record['service_name'],
                        'service_item_number' =>$record['service_item_no'],
                        'quantity'    => 1,
                        'price'       =>$record['price'],
                        'incentive'   =>$record['incentive'],
                        'penalty'     =>$record['penalty'],
                        'tat_date'    => $record['tat_date'],
                        'incentive_tat_date'    => $record['inc_tat_date'],
                        'report_status'     => $record['report_status'],
                        'additional_charges' => $record['additional_charge'],
                        'additional_charge_notes' => $record['additional_charge_notes'],
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    ];
                    $bill_items1=DB::table('billing_items')->insertGetId($billing_item_data1);

                    $jaf_add_attachment = DB::table('jaf_additional_charge_attachments')->where(['jaf_id'=>$record['jaf_id']])->get();

                    if(count($jaf_add_attachment)>0 && $record['is_charge_allowed']==1)
                    {
                        foreach($jaf_add_attachment as $attach)
                        {
                            DB::table('billing_additional_charge_attachments')->where(['billing_id'=>$billing_r->id,'service_id'=>$attach->service_id,'service_item_number'=>$attach->service_item_number])->delete();

                            $jaf_file_path = public_path().'/uploads/jaf/additional-charge/';

                            $bill_file_path = public_path().'/uploads/billings/additional-charge/';

                            if(File::exists($bill_file_path.$attach->file_name))
                            {
                                File::delete($bill_file_path.$attach->file_name);
                            }

                            if(File::exists($jaf_file_path.$attach->file_name))
                            {
                                File::copy($jaf_file_path.$attach->file_name,$bill_file_path.$attach->file_name);
                            }

                            DB::table('billing_additional_charge_attachments')->insert([
                                'billing_id' => $billing_r->id,
                                'billing_details_id' => $bill_items1,
                                'business_id' => $attach->business_id,
                                'service_id' => $attach->service_id,
                                'service_item_number' => $attach->service_item_number,
                                'file_name' => $attach->file_name,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
            }
        }

        // dd($array_result);

        $api_array=$this->apiPrice($start_date,$today_date);

        // dd($api_array);

        if(count($api_array)>0)
        {
            foreach($api_array as $record)
            {
                $invoice_no = strtoupper(substr('BCD', 0, 3)).date("-ymds");

                $billing_r=DB::table('billings')
                    ->where(['business_id'=>$record['business_id']])
                    ->whereDate('start_date','=',$start_date)
                    ->whereDate('end_date','=',$today_date)
                    ->first();
                if($billing_r==NULL)
                {
                    $billing_data=[
                    'parent_id'   => $record['parent_id'],
                    'business_id' => $record['business_id'],
                    'user_id'     => $record['business_id'],
                    // 'invoice_id'  => 'invoice_no'.Str::random(10),
                    'start_date'  => date('Y-m-d H:i:s',strtotime($start_date)),
                    'end_date'  => date('Y-m-d H:i:s',strtotime($today_date)),
                    'created_at'=> date('Y-m-d H:i:s'),
                    ];
                    $bill=DB::table('billings')->insertGetId($billing_data);

                    DB::table('billings')->where(['id'=>$bill])->update([
                        'invoice_id' => $invoice_no.$bill
                    ]);

                    $billing_item_data=[
                    'billing_id'  => $bill,
                    'parent_id'   => $record['parent_id'],
                    'business_id' => $record['business_id'],
                    'user_id'     => $record['business_id'],
                    'service_id'  => $record['service_id'],
                    'service_name'=> $record['service_name'],
                    'service_data'=> json_encode($record['service_data']),
                    'service_item_number' =>1,
                    'quantity'    => $record['qty'],
                    'price'       =>$record['price'],
                    'tat_date'    => $record['tat_date'],
                    'incentive_tat_date'    => $record['incentive_tat_date'],
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s')
                    ];
                    $bill_items=DB::table('billing_items')->insert($billing_item_data);
                }
                else{
                    DB::table('billing_items')->where(['billing_id'=>$billing_r->id,'service_data'=>json_encode($record['service_data'])])->delete();
                    $billing_item_data1=[
                        'billing_id'  => $billing_r->id,
                        'parent_id'   => $record['parent_id'],
                        'business_id' => $record['business_id'],
                        'user_id'     => $record['business_id'],
                        'service_id'  => $record['service_id'],
                        'service_name'=> $record['service_name'],
                        'service_data'=> json_encode($record['service_data']),
                        'service_item_number' =>1,
                        'quantity'    => $record['qty'],
                        'price'       =>$record['price'],
                        'tat_date'    => $record['tat_date'],
                        'incentive_tat_date'    => $record['incentive_tat_date'],
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                    ];
                    $bill_items1=DB::table('billing_items')->insert($billing_item_data1);
                }
            }
        }

        // Calculating the total price of billing items
        if(count($array_result) > 0 || count($api_array) >0)
        {
            foreach($customers as $cust_id)
            {
                $tax = 0;

                $tax_amount = 0;

                $billing_record=DB::table('billings as b')
                    ->select('bi.price','bi.incentive','bi.penalty','bi.id','bi.additional_charges')
                    ->join('billing_items as bi','b.id','=','bi.billing_id')
                    ->whereDate('b.start_date','=',$start_date)
                    ->whereDate('b.end_date','=',$today_date)
                    ->where(['bi.business_id'=>$cust_id])
                    ->orderBy('b.business_id','asc')
                    ->get();
                if(count($billing_record)>0)
                {
                    $total_price=0;

                    $sub_total_price=0;

                    foreach($billing_record as $record)
                    {
                        $price = 0;

                        $price = $record->price;

                        $price = number_format($price + ($price * ($record->incentive/100)),2);

                        $price = number_format($price - ($price * ($record->penalty/100)),2);

                        $price = number_format($price + $record->additional_charges,2);

                        DB::table('billing_items')->where(['id'=>$record->id])->update([
                            'total_check_price' => $price,
                            'final_total_check_price' => $price,
                        ]);

                        $sub_total_price = $total_price + $price;

                        $total_price= $sub_total_price;
                    }

                    $user_business=DB::table('user_businesses')->where(['business_id'=>$cust_id])->first();

                    if($user_business!=NULL)
                    {
                        if($user_business->gst_exempt==0)
                        {
                            $tax= 18.00;
                            
                            $tax_amount = number_format(str_replace(",","",number_format($total_price * ($tax/100),2)),2,".","");

                            $total_price = $total_price +  $tax_amount;
                        }
                    }
                    DB::table('billings as b')
                    ->where(['b.business_id'=>$cust_id])
                    ->whereDate('b.start_date','=',$start_date)
                    ->whereDate('b.end_date','=',$today_date)
                    ->update([
                        'tax' => $tax,
                        'tax_amount' => $tax_amount,
                        'sub_total' => $sub_total_price,
                        'total_amount'=>  $total_price,
                        'updated_at'=> date('Y-m-d H:i:s')
                    ]);

                    // $users = DB::table('users as u')
                    //         ->where(['u.id'=>$cust_id,'u.user_type'=>'client'])
                    //         ->first();

                    // $bill=DB::table('billings')
                    //         ->where(['b.business_id'=>$cust_id])
                    //         ->whereDate('b.start_date','=',$start_date)
                    //         ->whereDate('b.end_date','=',$today_date)
                    //         ->first();

                    // $email = $users->email;
                    // $name  = $users->first_name;
                    // $business_id = $users->parent_id;
                    // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    
                    // $data  = array('name'=>$name,'email'=>$email,'user'=>$users,'bill'=>$bill,'sender'=>$sender);
        
                    // Mail::send(['html'=>'mails.billing-notify'], $data, function($message) use($email,$name) {
                    //     $message->to($email, $name)->subject
                    //         ('myBCD System - Billing Notification');
                    //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    // });
                }
            }
        }
        
        return 0;
    }

    public function apiPrice($start_date,$end_date)
    {
        // $parent_id=Auth::user()->business_id;
        $customers=$this->customers_list();

        $incentive=0;
        $penalty=0;

        $array_result=[];
        if(count($customers)>0)
        {
            foreach($customers as $cust_id)
            {
                
                $aadhars=DB::table('aadhar_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.aadhar_number')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($aadhars)>0)
                {
                    foreach($aadhars as $aadhar)
                    {
                        $data=[];
                        $data=['Aadhar Number'=> $aadhar->aadhar_number];
                        $array_result[]=['parent_id'=>$aadhar->parent_id,'business_id'=>$cust_id,'service_id'=>$aadhar->service_id,'service_name'=>$aadhar->service_name,'service_data'=>$data,'price'=>$aadhar->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $pans=DB::table('pan_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.pan_number')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($pans)>0)
                {
                    foreach($pans as $pan)
                    {
                        $data=[];
                        $data=['PAN Number'=> $pan->pan_number];
                        $array_result[]=['parent_id'=>$pan->parent_id,'business_id'=>$cust_id,'service_id'=>$pan->service_id,'service_name'=>$pan->service_name,'service_data'=>$data,'price'=>$pan->total_price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $voter_ids=DB::table('voter_id_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.voter_id_number')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($voter_ids)>0)
                {
                    foreach($voter_ids as $voter_id)
                    {
                        $data=[];
                        $data=['Voter ID Number'=> $voter_id->voter_id_number];
                        $array_result[]=['parent_id'=>$voter_id->parent_id,'business_id'=>$cust_id,'service_id'=>$voter_id->service_id,'service_name'=>$voter_id->service_name,'service_data'=>$data,'price'=>$voter_id->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }
            
                $rcs=DB::table('rc_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.rc_number')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($rcs)>0)
                {
                    foreach($rcs as $rc)
                    {
                        $data=[];
                        $data=['RC Number'=> $rc->rc_number];
                        $array_result[]=['parent_id'=>$rc->parent_id,'business_id'=>$cust_id,'service_id'=>$rc->service_id,'service_name'=>$rc->service_name,'service_data'=>$data,'price'=>$rc->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $dls=DB::table('dl_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.dl_number')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($dls)>0)
                {
                    foreach($dls as $dl)
                    {
                        $data=[];
                        $data=['DL Number'=> $dl->dl_number];
                        $array_result[]=['parent_id'=>$dl->parent_id,'business_id'=>$cust_id,'service_id'=>$dl->service_id,'service_name'=>$dl->service_name,'service_data'=>$data,'price'=>$dl->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $passports=DB::table('passport_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.file_number','a.dob')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($passports)>0)
                {
                    foreach($passports as $passport)
                    {
                        $data=[];
                        $data=['File Number'=> $passport->file_number,'DOB'=>$passport->dob];
                        $array_result[]=['parent_id'=>$passport->parent_id,'business_id'=>$cust_id,'service_id'=>$passport->service_id,'service_name'=>$passport->service_name,'service_data'=>$data,'price'=>$passport->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $banks=DB::table('bank_account_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.account_number','a.ifsc_code')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','a.service_id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($banks)>0)
                {
                    foreach($banks as $bank)
                    {
                        $data=[];
                        $data=['Account Number'=> $bank->account_number,'IFSC Code'=>$bank->ifsc_code];
                        $array_result[]=['parent_id'=>$bank->parent_id,'business_id'=>$cust_id,'service_id'=>$bank->service_id,'service_name'=>$bank->service_name,'service_data'=>$data,'price'=>$bank->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $gsts=DB::table('gst_checks as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.gst_number')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();

                if(count($gsts)>0)
                {
                    foreach($gsts as $gst)
                    {
                        $data=[];
                        $data=['GST Number'=> $gst->gst_number];
                        $array_result[]=['parent_id'=>$gst->parent_id,'business_id'=>$cust_id,'service_id'=>$gst->service_id,'service_name'=>$gst->service_name,'service_data'=>$data,'price'=>$gst->total_price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $telecoms=DB::table('telecom_check as a')
                ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.mobile_no')
                ->join('services as s','s.id','=','a.service_id')
                // ->join('check_prices as c','c.service_id','=','a.service_id')
                ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                // ->groupBy('a.service_id')
                ->get();
                
                if(count($telecoms)>0)
                {
                    foreach($telecoms as $telecom)
                    {
                        $data=[];
                        $data=['Mobile Number'=> $telecom->mobile_no];
                        $array_result[]=['parent_id'=>$telecom->parent_id,'business_id'=>$cust_id,'service_id'=>$telecom->service_id,'service_name'=>$telecom->service_name,'service_data'=>$data,'price'=>$telecom->total_price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }
                
                $e_courts=DB::table('e_court_checks as a')
                ->select('s.id as service_id','s.name as service_name','a.name','a.father_name','a.address','a.user_id','a.created_at','a.price','a.parent_id')
                ->join('services as s','s.id','=','a.service_id')
                ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$cust_id])
                ->whereDate('a.created_at','>=',$start_date)
                ->whereDate('a.created_at','<=',$end_date)
                ->whereNULL('a.candidate_id')
                ->get();


                if(count($e_courts)>0)
                {
                    foreach($e_courts as $item)
                    {
                        $data = [];

                        $data=['Name'=>$item->name,'Father Name'=>$item->father_name,'Address'=>$item->address];

                        $array_result[]=['parent_id'=>$item->parent_id,'business_id'=>$cust_id,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_data'=>$data,'price'=>$item->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $upis=DB::table('upi_checks as a')
                        ->select('s.id as service_id','s.name as service_name','a.name','a.upi_id','a.user_id','a.created_at','a.price','a.parent_id')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$cust_id])
                        ->whereDate('a.created_at','>=',$start_date)
                        ->whereDate('a.created_at','<=',$end_date)
                        ->whereNULL('a.candidate_id')
                        ->get();

                if(count($upis)>0)
                {
                    foreach($upis as $item)
                    {
                        $data = [];

                        $data=['UPI ID'=>$item->upi_id,'Name'=>$item->name];

                        $array_result[]=['parent_id'=>$item->parent_id,'business_id'=>$cust_id,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_data'=>$data,'price'=>$item->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }

                $cins=DB::table('cin_checks as a')
                        ->select('s.id as service_id','s.name as service_name','a.company_name','a.cin_number','a.user_id','a.created_at','a.price','a.parent_id')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$cust_id])
                        ->whereDate('a.created_at','>=',$start_date)
                        ->whereDate('a.created_at','<=',$end_date)
                        ->whereNULL('a.candidate_id')
                        ->get();

                if(count($cins)>0)
                {
                    foreach($cins as $item)
                    {
                        $data = [];

                        $data=['CIN Number'=>$item->cin_number,'Company Name'=>$item->company_name];

                        $array_result[]=['parent_id'=>$item->parent_id,'business_id'=>$cust_id,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_data'=>$data,'price'=>$item->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                    }
                }
            }
        }
        

       return $array_result;
    }

    public function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

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

    public function roundUp ( $value, $precision ) { 
        $pow = pow ( 10, $precision ); 
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
    } 
}
