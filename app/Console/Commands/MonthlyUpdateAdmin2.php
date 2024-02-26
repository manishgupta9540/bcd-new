<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MonthlyUpdateAdmin2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthlyadmin2:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a monthly record for Admin on last day of month to calculate price';

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
        if(Auth::user()->user_type=='customer')
            $business_id = Auth::user()->business_id;
        elseif(Auth::user()->user_type=='user')
        {
            $record=DB::table('users')->where(['id'=>Auth::user()->business_id])->first();
            if($record->user_type=='customer')
                $business_id=Auth::user()->business_id;
        }
        $array_result=[];
        $items = DB::table('users as u')
        ->select('u.id')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'customer','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])->get();
        foreach($items as $item){
            $array_result[]=$item->id;
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
        $customers=$this->customers_list();
        // $array_result=[];
        $today_date=date('Y-m-t');

        $start_date=date('Y-m-16');

        $api_array=$this->apiPrice($start_date,$today_date);

        // dd($api_array);

        if(count($api_array)>0)
        {
            foreach($api_array as $record)
            {
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
                    'invoice_id'  => 'invoice_no'.Str::random(10),
                    'start_date'  => date('Y-m-d h:i:s',strtotime($start_date)),
                    'end_date'  => date('Y-m-d h:i:s',strtotime($today_date)),
                    'created_at'=> date('Y-m-d h:i:s'),
                    ];
                    $bill=DB::table('billings')->insertGetId($billing_data);

                    $billing_item_data=[
                    'billing_id'  => $bill,
                    'parent_id'   => $record['parent_id'],
                    'business_id' => $record['business_id'],
                    'user_id'     => $record['business_id'],
                    'service_id'  => $record['service_id'],
                    'service_name'=> $record['service_name'],
                    'service_item_number' =>1,
                    'quantity'    => $record['qty'],
                    'price'       =>$record['price'],
                    'created_at'=> date('Y-m-d h:i:s'),
                    'updated_at'=> date('Y-m-d h:i:s')
                    ];
                    $bill_items=DB::table('billing_items')->insert($billing_item_data);
                }
                else{

                    $billing_item_data1=[
                        'billing_id'  => $billing_r->id,
                        'parent_id'   => $record['parent_id'],
                        'business_id' => $record['business_id'],
                        'user_id'     => $record['business_id'],
                        'service_id'  => $record['service_id'],
                        'service_name'=> $record['service_name'],
                        'service_item_number' =>1,
                        'quantity'    => $record['qty'],
                        'price'       =>$record['price'],
                        'created_at'=> date('Y-m-d h:i:s'),
                        'updated_at'=> date('Y-m-d h:i:s')
                    ];
                    $bill_items1=DB::table('billing_items')->insert($billing_item_data1);
                }
            }
        }

        if(count($api_array) >0)
        {
            foreach($customers as $cust_id)
            {
                $billing_record=DB::table('billings as b')
                    ->select(DB::raw('sum(bi.price) as total_price'))
                    ->join('billing_items as bi','b.id','=','bi.billing_id')
                    ->where(['b.business_id'=>$cust_id])
                    ->first();
                if($billing_record!=NULL)
                {
                    DB::table('billings as b')
                    ->where(['b.business_id'=>$cust_id])
                    ->whereDate('b.start_date','=',$start_date)
                    ->whereDate('b.end_date','=',$today_date)
                    ->update([
                        'total_amount'=> $billing_record->total_price,
                        'updated_at'=> date('Y-m-d h:i:s')
                    ]);
                }
            }
        }
        return 0;
    }

    public function apiPrice($start_date,$end_date)
    {
        // $parent_id=Auth::user()->business_id;
        $customers=$this->customers_list();

        $array_result=[];
        foreach($customers as $cust_id)
        {
            
            $aadhar=DB::table('aadhar_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($aadhar!=NULL)
            {
                $array_result[]=['parent_id'=>$aadhar->parent_id,'business_id'=>$cust_id,'service_id'=>$aadhar->service_id,'service_name'=>$aadhar->service_name,'price'=>$aadhar->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$aadhar->no_of_hits];
            }

            $pan=DB::table('pan_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($pan!=NULL)
            {
                $array_result[]=['parent_id'=>$pan->parent_id,'business_id'=>$cust_id,'service_id'=>$pan->service_id,'service_name'=>$pan->service_name,'price'=>$pan->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$pan->no_of_hits];
            }

            $voter_id=DB::table('voter_id_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($voter_id!=NULL)
            {
                $array_result[]=['parent_id'=>$voter_id->parent_id,'business_id'=>$cust_id,'service_id'=>$voter_id->service_id,'service_name'=>$voter_id->service_name,'price'=>$voter_id->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$voter_id->no_of_hits];
            }
        
            $rc=DB::table('rc_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($rc!=NULL)
            {
                $array_result[]=['parent_id'=>$rc->parent_id,'business_id'=>$cust_id,'service_id'=>$rc->service_id,'service_name'=>$rc->service_name,'price'=>$rc->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$rc->no_of_hits];
            }

            $dl=DB::table('dl_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($dl!=NULL)
            {
                $array_result[]=['parent_id'=>$dl->parent_id,'business_id'=>$cust_id,'service_id'=>$dl->service_id,'service_name'=>$dl->service_name,'price'=>$dl->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$dl->no_of_hits];
            }

            $passport=DB::table('passport_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($passport!=NULL)
            {
                $array_result[]=['parent_id'=>$passport->parent_id,'business_id'=>$cust_id,'service_id'=>$passport->service_id,'service_name'=>$passport->service_name,'price'=>$passport->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$passport->no_of_hits];
            }

            $bank=DB::table('bank_account_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','a.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($bank!=NULL)
            {
                $array_result[]=['parent_id'=>$bank->parent_id,'business_id'=>$cust_id,'service_id'=>$bank->service_id,'service_name'=>$bank->service_name,'price'=>$bank->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$bank->no_of_hits];
            }

            $gst=DB::table('gst_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($gst!=NULL)
            {
                $array_result[]=['parent_id'=>$gst->parent_id,'business_id'=>$cust_id,'service_id'=>$gst->service_id,'service_name'=>$gst->service_name,'price'=>$gst->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$gst->no_of_hits];
            }

            $telecom=DB::table('telecom_check as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();
            
            if($telecom!=NULL)
            {
                $array_result[]=['parent_id'=>$telecom->parent_id,'business_id'=>$cust_id,'service_id'=>$telecom->service_id,'service_name'=>$telecom->service_name,'price'=>$telecom->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$telecom->no_of_hits];
            }            
        }

       return $array_result;
    }
}
