<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesDataExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Exports\MISDataExport;
use App\Exports\OPSExport;
use App\Exports\DailyDataExport;
use App\Exports\ProgressDataExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Models\Admin\ProgressExportLog;
use App\Imports\MISDataFileImport;

class ExcelController extends Controller
{
   /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        ini_set('max_execution_time', '0');
    }

    public function salesTracker(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;
        // dd($request->sales_month);

        $rules= 
        [
            'type' => 'required|in:daily,weekly,monthly,quaterly,yearly',
            'month' => 'required_if:type,monthly',
            'quater' => 'required_if:type,quaterly',
            'year' => 'required_if:type,monthly,quaterly,yearly|date_format:Y'
            
        ];
        $custom=[
            'type.required' => 'The duration type field is required',
            'type.in' => 'Select the Specific Duration type',
            'month.required_if' => 'The month field is required',
            'quater.required_if' => 'The quater field is required',
            'year.required_if' => 'The year field is required'
        ];
        $validator = Validator::make($request->all(), $rules, $custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $customer_id = $request->customer;

        // dd($request->month);

        // dd(date('m',strtotime('2012-'.$request->month.'-25')));

        if($request->month==date('m') && $request->year == date('Y'))
        {
            $current_day = date('d');
        }
        else
        {
            $current_day = date('t',strtotime(date('Y-m-d',strtotime($request->year.'-'.$request->month.'-01'))));
        }

        $today_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$current_day));

        $start_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.'01'));

        // dd(date('Y').'-'.$request->month.'-'.$current_day);

        if(stripos($request->type,'weekly')!==false)
        {
            $today_date = date('Y-m-d');

            $start_date = date('Y-m-d',strtotime('- 6 days'));
        }
        else if(stripos($request->type,'monthly')!==false)
        {

            $current_day = date('d');

            if($request->month==date('m') && $request->year == date('Y'))
            {
                $current_day = date('d');
            }
            else
            {
                $current_day = date('t',strtotime(date('Y-m-d',strtotime($request->year.'-'.$request->month.'-01'))));
            }

            $today_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$current_day));

            $start_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.'01'));
        }
        else if(stripos($request->type,'quaterly')!==false)
        {
            // Apr to June
            if(stripos($request->quater,'q1')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'06'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'04'.'-'.'01'));
            }
            // July to Sep
            else if (stripos($request->quater,'q2')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'09'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'07'.'-'.'01'));
            }
            // OCT to Dec
            else if (stripos($request->quater,'q3')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'12'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'10'.'-'.'01'));
            }
            // Jan to Mar
            else if (stripos($request->quater,'q4')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'03'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));
            }
        }
        else if(stripos($request->type,'yearly')!==false)
        {
            $today_date = date('Y-m-d',strtotime($request->year.'-'.'12'.'-'.date('t')));

            $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));

            if($request->year==date('Y'))
            {
                // $current_month = date('m');

                $today_date = date('Y-m-d');

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));
            }
        }

        $type = $request->type;
        
        // echo "done";

        $path=public_path().'/uploads/sales-export/'.$user_id.'/';

        $file_name = 'sales-tracker-'.date('YmdHis').'.xlsx';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

        if($type!='')
        {
            Excel::store(new SalesDataExport($start_date,$today_date,$business_id,$customer_id,$type),'/uploads/sales-export/'.$user_id.'/'.$file_name,'real_public');

            //// return Excel::download(new SalesDataExport($start_date,$today_date,$business_id),'sales-data.xlsx');

            // Session()->forget('sales_export_data');

            // $sales_export_data = [];

            // $avg_week_arr = [];

            // $avg_month_arr = [];

            // $from_date = $start_date;

            // $to_date = $today_date;

            // $clients = DB::table('users as u')
            //         ->select('u.id','ub.company_name')
            //         ->join('user_businesses as ub','ub.business_id','=','u.business_id')
            //         ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            //         ->whereNotIn('u.id',[$business_id]); 
            //         if($customer_id!=NULL)
            //         {
            //             $clients->whereIn('u.id',$customer_id);
            //         }
            // $clients = $clients->pluck('ub.company_name','u.id')->all();

            // foreach($clients as $key => $value)
            // {
            //     // Avg. No of Case Weekly

            //     $avg_week = 0.00;

            //     if(stripos($type,'weekly')!==false)
            //     {
            //         $avg_week = DB::table('candidate_reinitiates')
            //                         ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
            //                         ->whereDate('created_at','>=',$from_date)
            //                         ->whereDate('created_at','<=',$to_date)
            //                         ->count();

            //     }
            //     else if(stripos($type,'monthly')!==false)
            //     {
            //         $no_of_week = 1;

            //         for($i=1;$i<=date('d',strtotime($to_date));$i++)
            //         {
            //             if($i==8 || $i==15 || $i==22 || $i==29)
            //                 $no_of_week++;
            //         }

            //         $monthly_case = DB::table('candidate_reinitiates')
            //                     ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
            //                     ->whereDate('created_at','>=',$from_date)
            //                     ->whereDate('created_at','<=',$to_date)
            //                     ->count();
                        
            //         $avg_week = number_format($monthly_case/$no_of_week,2);

            //     }
            //     else if(stripos($type,'quaterly')!==false)
            //     {
            //         $avg_m_week = 0.00;
                    
            //         for($i=0;$i<3;$i++)
            //         {
            //             $start_date = date('Y-m-01',strtotime($from_date.' + '.$i.' month'));

            //             $end_date = date('Y-m-t',strtotime($from_date.' + '.$i.' month'));

            //             $no_of_week = 1;

            //             for($j=1;$j<=date('d',strtotime($end_date));$j++)
            //             {
            
            //                 if($j==8 || $j==15 || $j==22 || $j==29)
            //                     $no_of_week++;
            //             }

            //             // dd($no_of_week);

            //             $monthly_case = DB::table('candidate_reinitiates')
            //                         ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
            //                         ->whereDate('created_at','>=',$start_date)
            //                         ->whereDate('created_at','<=',$end_date)
            //                         ->count();
            //             // dd($monthly_case);
                        
            //             $avg_m_week = number_format($avg_m_week + ($monthly_case / $no_of_week),2);
            //             // dd($avg_m_week);
            //         }

            //         $avg_week = $avg_m_week;
            //     }
            //     else if(stripos($type,'yearly')!==false)
            //     {
            //         $avg_m_week = 0;

            //         $no_of_month = date('n',strtotime($to_date));
            //         // dd($no_of_month);
            //         for($i=0;$i<$no_of_month;$i++)
            //         {
            //             $start_date = date('Y-m-01',strtotime($from_date.' + '.$i.' month'));

            //             $end_date = date('Y-m-t',strtotime($from_date.' + '.$i.' month'));

            //             $no_of_week = 1;

            //             for($j=1;$j<=date('d',strtotime($end_date));$j++)
            //             {
            
            //                 if($j==8 || $j==15 || $j==22 || $j==29)
            //                     $no_of_week++;
            //             }
            
            //             $monthly_case = DB::table('candidate_reinitiates')
            //                         ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
            //                         ->whereDate('created_at','>=',$start_date)
            //                         ->whereDate('created_at','<=',$end_date)
            //                         ->count();
                            
            //             $avg_m_week = $avg_m_week + number_format($monthly_case/$no_of_week,2);
            //         }

            //         $avg_week = $avg_m_week;
            //     }

            //     array_push($avg_week_arr,strval($avg_week));

            //     // Avg. No of Case Monthly

            //     $monthly_case = 0.00;

            //     if(stripos($type,'weekly')!==false || stripos($type,'monthly')!==false)
            //     {
            //         $monthly_case = DB::table('candidate_reinitiates')
            //                 ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
            //                 ->whereDate('created_at','>=',$from_date)
            //                 ->whereDate('created_at','<=',$to_date)
            //                 ->count();
            //     }
            //     else if (stripos($type,'quaterly')!==false)
            //     {
            //         $monthly_case = DB::table('candidate_reinitiates')
            //                 ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
            //                 ->whereDate('created_at','>=',$from_date)
            //                 ->whereDate('created_at','<=',$to_date)
            //                 ->count();

            //         $monthly_case = number_format($monthly_case / 3, 2);
            //     }
            //     else if (stripos($type,'yearly')!==false)
            //     {
            //         $no_of_month = date('n',strtotime($to_date));

            //         $monthly_case = DB::table('candidate_reinitiates')
            //                 ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
            //                 ->whereDate('created_at','>=',$from_date)
            //                 ->whereDate('created_at','<=',$to_date)
            //                 ->count();

            //         $monthly_case = number_format($monthly_case / $no_of_month, 2);
            //     }

            //     array_push($avg_month_arr,strval($monthly_case));

            // }

            // $sales_export_data=['type'=>$type,'clients'=>$clients,'weekly_case'=>$avg_week_arr,'monthly_case'=>$avg_month_arr,'from_date'=>$from_date,'to_date'=>$to_date,'url'=>url('/').'/uploads/sales-export/'.$user_id.'/'.$file_name];

            // session()->put('sales_export_data', $sales_export_data);

            return response()->json([
                'success' => true,
                'url' => url('/').'/uploads/sales-export/'.$user_id.'/'.$file_name
            ]);


        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'No Data Found, Try Again with Some Other Filter Option !!'
            ]);
        }
    }

    public function salesDashboard(Request $request)
    {
        $business_id = Auth::user()->business_id;

        // if(session()->exists('sales_export_data'))
        // {
        //     $url = "#";
        //     $sales_data = session()->get('sales_export_data');

        //     $customer_id = array_keys($sales_data['clients']);

        //     $company_name = array_values($sales_data['clients']);

        //     $avg_week_case = $sales_data['weekly_case'];

        //     $avg_monthly_case = $sales_data['monthly_case'];

        //     $url = $sales_data['url'];

        //     $type = $sales_data['type'];

        //     $from_date = $sales_data['from_date'];

        //     $to_date = $sales_data['to_date'];

        //     return view('admin.settings.sales-tracker.index',compact('company_name','avg_week_case','avg_monthly_case','type','from_date','to_date','customer_id','url'));
        // }
        // else
        // {
        //     return redirect('/home');
        // }

        $type = 'weekly';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-d',strtotime('- 6 days'));

        $customer_id=NULL;

        $year = $request->year!='' ? $request->year : date('Y');

        $month = $request->month!='' ? $request->month : date('m');

        if($request->get('type')!='')
        {
            if(stripos($request->type,'weekly')!==false)
            {
                $to_date = date('Y-m-d');

                $from_date = date('Y-m-d',strtotime('- 6 days'));
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                $current_day = date('d');

                if($month==date('m') && $year == date('Y'))
                {
                    $current_day = date('d');
                }
                else
                {
                    $current_day = date('t',strtotime(date('Y-m-d',strtotime($year.'-'.$month.'-01'))));
                }

                $to_date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$current_day));

                $from_date = date('Y-m-d',strtotime($year.'-'.$month.'-'.'01'));
            }
            else if(stripos($request->type,'quaterly')!==false)
            {
                // Apr to June
                if(stripos($request->quater,'q1')!==false)
                {
                    $to_date = date('Y-m-t',strtotime($year.'-'.'06'));

                    $from_date = date('Y-m-d',strtotime($year.'-'.'04'.'-'.'01'));
                }
                // July to Sep
                else if (stripos($request->quater,'q2')!==false)
                {
                    $to_date = date('Y-m-t',strtotime($year.'-'.'09'));

                    $from_date = date('Y-m-d',strtotime($year.'-'.'07'.'-'.'01'));
                }
                // OCT to Dec
                else if (stripos($request->quater,'q3')!==false)
                {
                    $to_date = date('Y-m-t',strtotime($year.'-'.'12'));

                    $from_date = date('Y-m-d',strtotime($year.'-'.'10'.'-'.'01'));
                }
                // Jan to Mar
                else if (stripos($request->quater,'q4')!==false)
                {
                    $to_date = date('Y-m-t',strtotime($year.'-'.'03'));

                    $from_date = date('Y-m-d',strtotime($year.'-'.'01'.'-'.'01'));
                }
            }
            else if(stripos($request->type,'yearly')!==false)
            {
                $to_date = date('Y-m-d',strtotime($year.'-'.'12'.'-'.date('t')));

                $from_date = date('Y-m-d',strtotime($year.'-'.'01'.'-'.'01'));

                if($year==date('Y'))
                {
                    // $current_month = date('m');

                    $to_date = date('Y-m-d');

                    $from_date = date('Y-m-d',strtotime($year.'-'.'01'.'-'.'01'));
                }
            }

            $type = $request->get('type');
        }

        if($request->get('customer_id')!=null)
        {
            $customer_id = explode(',',$request->get('customer_id'));
        }

        $avg_week_arr = [];

        $avg_month_arr = [];

        $clients = DB::table('users as u')
                    ->select('u.id','ub.company_name')
                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id]); 
                    if($customer_id!=NULL)
                    {
                        $clients->whereIn('u.id',$customer_id);
                    }
        $clients = $clients->pluck('ub.company_name','u.id')->all();

        $customer_id = array_keys($clients);

        $company_name =  array_values($clients);

        $customers = DB::table('users as u')
        ->select('u.*','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
        ->whereNotIn('u.id',[Auth::user()->business_id])
        ->get();

        $avg_week_case = [];
        
        $avg_monthly_case = [];

        if($request->ajax())
        {
            foreach($clients as $key => $value)
            {
                // Avg. No of Case Weekly

                $avg_week = 0.00;

                if(stripos($type,'weekly')!==false)
                {
                    $avg_week = DB::table('candidate_reinitiates')
                                    ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
                                    ->whereDate('created_at','>=',$from_date)
                                    ->whereDate('created_at','<=',$to_date)
                                    ->count();

                }
                else if(stripos($type,'monthly')!==false)
                {
                    $no_of_week = 1;

                    for($i=1;$i<=date('d',strtotime($to_date));$i++)
                    {
                        if($i==8 || $i==15 || $i==22 || $i==29)
                            $no_of_week++;
                    }

                    $monthly_case = DB::table('candidate_reinitiates')
                                ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
                                ->whereDate('created_at','>=',$from_date)
                                ->whereDate('created_at','<=',$to_date)
                                ->count();
                        
                    $avg_week = number_format($monthly_case/$no_of_week,2);

                }
                else if(stripos($type,'quaterly')!==false)
                {
                    $avg_m_week = 0.00;
                    
                    for($i=0;$i<3;$i++)
                    {
                        $start_date = date('Y-m-01',strtotime($from_date.' + '.$i.' month'));

                        $end_date = date('Y-m-t',strtotime($from_date.' + '.$i.' month'));

                        $no_of_week = 1;

                        for($j=1;$j<=date('d',strtotime($end_date));$j++)
                        {
            
                            if($j==8 || $j==15 || $j==22 || $j==29)
                                $no_of_week++;
                        }

                        // dd($no_of_week);

                        $monthly_case = DB::table('candidate_reinitiates')
                                    ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
                                    ->whereDate('created_at','>=',$start_date)
                                    ->whereDate('created_at','<=',$end_date)
                                    ->count();
                        // dd($monthly_case);
                        
                        $avg_m_week = number_format($avg_m_week + ($monthly_case / $no_of_week),2);
                        // dd($avg_m_week);
                    }

                    $avg_week = $avg_m_week;
                }
                else if(stripos($type,'yearly')!==false)
                {
                    $avg_m_week = 0;

                    $no_of_month = date('n',strtotime($to_date));
                    // dd($no_of_month);
                    for($i=0;$i<$no_of_month;$i++)
                    {
                        $start_date = date('Y-m-01',strtotime($from_date.' + '.$i.' month'));

                        $end_date = date('Y-m-t',strtotime($from_date.' + '.$i.' month'));

                        $no_of_week = 1;

                        for($j=1;$j<=date('d',strtotime($end_date));$j++)
                        {
            
                            if($j==8 || $j==15 || $j==22 || $j==29)
                                $no_of_week++;
                        }
            
                        $monthly_case = DB::table('candidate_reinitiates')
                                    ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
                                    ->whereDate('created_at','>=',$start_date)
                                    ->whereDate('created_at','<=',$end_date)
                                    ->count();
                            
                        $avg_m_week = $avg_m_week + number_format($monthly_case/$no_of_week,2);
                    }

                    $avg_week = $avg_m_week;
                }

                array_push($avg_week_arr,strval($avg_week));

                // Avg. No of Case Monthly

                $monthly_case = 0.00;

                if(stripos($type,'weekly')!==false || stripos($type,'monthly')!==false)
                {
                    $monthly_case = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$from_date)
                            ->whereDate('created_at','<=',$to_date)
                            ->count();
                }
                else if (stripos($type,'quaterly')!==false)
                {
                    $monthly_case = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$from_date)
                            ->whereDate('created_at','<=',$to_date)
                            ->count();

                    $monthly_case = number_format($monthly_case / 3, 2);
                }
                else if (stripos($type,'yearly')!==false)
                {
                    $no_of_month = date('n',strtotime($to_date));

                    $monthly_case = DB::table('candidate_reinitiates')
                            ->where(['business_id'=>$key,'user_type'=>'candidate','is_deleted'=>'0'])
                            ->whereDate('created_at','>=',$from_date)
                            ->whereDate('created_at','<=',$to_date)
                            ->count();

                    $monthly_case = number_format($monthly_case / $no_of_month, 2);
                }

                array_push($avg_month_arr,strval($monthly_case));

            }
        
            $avg_week_case = $avg_week_arr;

            $avg_monthly_case = $avg_month_arr;

            return view('admin.settings.sales-tracker.ajax',compact('company_name','avg_week_case','avg_monthly_case','type','from_date','to_date','customer_id','customers'));
        }
        else
        {
            return view('admin.settings.sales-tracker.index',compact('company_name','avg_week_case','avg_monthly_case','type','from_date','to_date','customer_id','customers'));
        }


    }

    public function salesExport(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

        $customer_id = $request->customer_id;

        $year = $request->year!='' ? $request->year : date('Y');

        $month = $request->month!='' ? $request->month : date('m');

        // dd($request->month);

        // dd(date('m',strtotime('2012-'.$request->month.'-25')));

        if($request->month==date('m') && $request->year == date('Y'))
        {
            $current_day = date('d');
        }
        else
        {
            $current_day = date('t',strtotime(date('Y-m-d',strtotime($request->year.'-'.$request->month.'-01'))));
        }

        $today_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$current_day));

        $start_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.'01'));

        // dd(date('Y').'-'.$request->month.'-'.$current_day);

        if(stripos($request->type,'weekly')!==false)
        {
            $today_date = date('Y-m-d');

            $start_date = date('Y-m-d',strtotime('- 6 days'));
        }
        else if(stripos($request->type,'monthly')!==false)
        {

            $current_day = date('d');

            if($request->month==date('m') && $request->year == date('Y'))
            {
                $current_day = date('d');
            }
            else
            {
                $current_day = date('t',strtotime(date('Y-m-d',strtotime($request->year.'-'.$request->month.'-01'))));
            }

            $today_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$current_day));

            $start_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.'01'));
        }
        else if(stripos($request->type,'quaterly')!==false)
        {
            // Apr to June
            if(stripos($request->quater,'q1')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'06'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'04'.'-'.'01'));
            }
            // July to Sep
            else if (stripos($request->quater,'q2')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'09'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'07'.'-'.'01'));
            }
            // OCT to Dec
            else if (stripos($request->quater,'q3')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'12'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'10'.'-'.'01'));
            }
            // Jan to Mar
            else if (stripos($request->quater,'q4')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'03'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));
            }
        }
        else if(stripos($request->type,'yearly')!==false)
        {
            $today_date = date('Y-m-d',strtotime($request->year.'-'.'12'.'-'.date('t')));

            $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));

            if($request->year==date('Y'))
            {
                // $current_month = date('m');

                $today_date = date('Y-m-d');

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));
            }
        }

        $type = $request->type;
        
        // echo "done";

        if($type!='')
        {
            $path=public_path().'/uploads/sales-export/'.$user_id.'/';

            $file_name = 'sales-tracker-'.date('YmdHis').'.xlsx';

            if(!File::exists($path))
            {
                File::makeDirectory($path, $mode = 0777, true, true);
            }

            if (File::exists($path)) 
            {
                File::cleanDirectory($path);
            }

            Excel::store(new SalesDataExport($start_date,$today_date,$business_id,$customer_id,$type),'/uploads/sales-export/'.$user_id.'/'.$file_name,'real_public');

            $file = Helper::createFileObject($path.$file_name);

            $parsed_array = Excel::toArray([], $file);
            $imported_data = array_splice($parsed_array[0], 1);
            array_shift($imported_data);
            //dd($imported_data);

           $viewRender = view('admin.settings.sales-tracker.template',compact('imported_data','type','start_date','today_date'))->render();

            return response()->json([
                'success' => true,
                'html' => $viewRender,
                'url' => url('/').'/uploads/sales-export/'.$user_id.'/'.$file_name
            ]);
        }
    }

    public function misExport(Request $request)
    {
        $user_id = Auth::user()->id;

        $business_id = Auth::user()->business_id;

        $today_date = date('Y-m-d');

        $start_date = date('Y-m-01',strtotime('-1 year'));

        $file_name = 'master-tracker-'.date('YmdHis').'.xlsx';

        $services = DB::table('services')
                        ->whereNull('business_id')
                        ->where('status',1)
                        ->whereNotIn('type_name',['e_court'])
                        ->orderBy('sort_number','asc')
                        ->get();

        $clients = DB::table('users as u')
                    ->select('u.*','ub.company_name')
                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->whereNotIn('u.id',[$business_id])
                    ->get();

        // dd($start_date);

        // echo "done";

        //return Excel::download(new MISDataExport($start_date,$today_date,$business_id),$file_name);

        $path=public_path().'/uploads/master-export/'.$user_id.'/';

        $file_name = 'master-tracker-'.date('YmdHis').'.xlsx';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

        Excel::store(new MISDataExport($start_date,$today_date,$business_id),'/uploads/master-export/'.$user_id.'/'.$file_name,'real_public');

        $file = Helper::createFileObject($path.$file_name);

        $parsed_array = Excel::toArray(new MISDataFileImport, $file);
        $imported_data = array_splice($parsed_array[0], 3);

        //dd($imported_data);

        $viewRender = view('admin.settings.master-tracker.template',compact('business_id','start_date','today_date','services','clients','imported_data'))->render();

        return response()->json([
            'status' => true,
            'html' => $viewRender,
            'url' => url('/').'/uploads/master-export/'.$user_id.'/'.$file_name
        ]);
    }

    public function masterDashboard(Request $request)
    {

        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

        $type = 'daily';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-d');

        $year = '2022';

        $customer_id=NULL;

        $client_company_name = [];

        $total_coc_user_arr = [];

        if($request->get('customer_id')!=null)
        {
            $customer_id = explode(',',$request->get('customer_id'));
        }

        if($request->get('type')!='')
        {
            if(stripos($request->type,'daily')!==false)
            {
                $from_date = date('Y-m-d');

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'weekly')!==false)
            {
                $from_date = date('Y-m-d',strtotime('- 6 days'));

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                $current_day = date('d');

                if($request->month==date('m') && $request->year == date('Y'))
                {
                    $current_day = date('d');
                }
                else
                {
                    $current_day = date('t',strtotime(date('Y-m-d',strtotime($request->year.'-'.$request->month.'-01'))));
                }

                $to_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$current_day));

                $from_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.'01'));
            }
        }

        $customers = DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
                    ->whereNotIn('u.id',[Auth::user()->business_id])
                    ->get();

        $client_company_name = DB::table('users as u')
                                ->select('b.company_name')
                                ->join('user_businesses as b','b.business_id','=','u.id')
                                ->where(['u.user_type'=>'client','u.parent_id'=>Auth::user()->business_id])
                                ->whereNotIn('u.id',[Auth::user()->business_id])
                                ->pluck('b.company_name')
                                ->all();

        foreach($customers as $key => $cust)
        {
            $users = DB::table('users')->where(['business_id'=>$cust->id,'user_type'=>'user','is_deleted'=>'0'])->count();

            array_push($total_coc_user_arr,$users);
        }

        // dd($customers);

        // dd($total_coc_user_arr);

        if($request->ajax())
        {
            return view('admin.settings.master-tracker.ajax',compact('client_company_name','total_coc_user_arr','type','from_date','to_date','customer_id','customers'));
        }
        else
        {
            return view('admin.settings.master-tracker.index',compact('client_company_name','total_coc_user_arr','type','from_date','to_date','customer_id','customers'));
        }
    }

    public function opsExport(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $u_id = Auth::user()->id;
        $rules= 
        [
            'type' => 'required|in:daily,weekly,monthly,quaterly,yearly',
            'month' => 'required_if:type,monthly',
            'quater' => 'required_if:type,quaterly',
            'year' => 'required_if:type,monthly,quaterly,yearly'
            
        ];
        $custom=[
            'type.required' => 'The duration type field is required',
            'type.in' => 'Select the Specific Duration type',
            'month.required_if' => 'The month field is required',
            'quater.required_if' => 'The quater field is required',
            'year.required_if' => 'The year field is required'
        ];
        $validator = Validator::make($request->all(), $rules, $custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // dd(1);
        $today_date = date('Y-m-d');

        $start_date = date('Y-m-d',strtotime('- 6 days'));

        if(stripos($request->type,'daily')!==false)
        {
            $today_date = date('Y-m-d');

            $start_date = date('Y-m-d');
        }
        else if(stripos($request->type,'weekly')!==false)
        {
            $today_date = date('Y-m-d');

            $start_date = date('Y-m-d',strtotime('- 6 days'));
        }
        else if(stripos($request->type,'monthly')!==false)
        {

            $current_day = date('d');

            if($request->month==date('m') && $request->year == date('Y'))
            {
                $current_day = date('d');
            }
            else
            {
                $current_day = date('t',strtotime(date('Y-m-d',strtotime($request->year.'-'.$request->month.'-01'))));
            }

            $today_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$current_day));

            $start_date = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.'01'));
        }
        else if(stripos($request->type,'quaterly')!==false)
        {
            // Apr to June
            if(stripos($request->quater,'q1')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'06'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'04'.'-'.'01'));
            }
            // July to Sep
            else if (stripos($request->quater,'q2')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'09'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'07'.'-'.'01'));
            }
            // OCT to Dec
            else if (stripos($request->quater,'q3')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'12'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'10'.'-'.'01'));
            }
            // Jan to Mar
            else if (stripos($request->quater,'q4')!==false)
            {
                $today_date = date('Y-m-t',strtotime($request->year.'-'.'03'));

                $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));
            }

        }
        else if(stripos($request->type,'yearly')!==false)
        {
            $today_date = date('Y-m-d',strtotime($request->year.'-'.'12'.'-'.date('t')));

            $start_date = date('Y-m-d',strtotime($request->year.'-'.'01'.'-'.'01'));

        }

        $customer_id = $request->customer;

        $user_id = $request->user;

        $path=public_path().'/uploads/ops-export/'.$u_id.'/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

        $file_name = 'ops-tracker-'.date('YmdHis').'.xlsx';

        // dd($start_date);

        // echo "done";
        $query = DB::table('candidate_reinitiates as u')
                        ->select('u.*','ub.company_name',DB::raw('@row  := @row  + 1 AS s_no'),'s.id as service_id','s.name as service_name','jf.check_item_number as item_no','s.verification_type','j.id as job_item_id','j.tat_type','j.client_tat as case_tat')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                        ->join('services as s','s.id','=','jf.service_id')
                        ->whereDate('u.created_at','>=',$start_date)
                        ->whereDate('u.created_at','<=',$today_date)
                        ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','j.jaf_status'=>'filled','is_deleted'=>0]);
                        if($customer_id!=NULL)
                        {
                            $query->whereIn('u.business_id',$customer_id);
                        }
                        if($user_id!=NULL)
                        {
                            $query->whereIn('u.created_by',$user_id);
                        }
                        $query = $query->get();
        
        // if(count($query)>0)
        // {
        //     return Excel::download(new OPSExport($start_date,$today_date,$business_id,$customer_id,$user_id),$file_name);
        // }
        // else
        //     return view('main-web.error-404-data');

        if(count($query)>0)
        {
            Excel::store(new OPSExport($start_date,$today_date,$business_id,$customer_id,$user_id),'/uploads/ops-export/'.$u_id.'/'.$file_name,'real_public');

            $file = Helper::createFileObject($path.$file_name);

            $parsed_array = Excel::toArray([], $file);
            $imported_data = array_splice($parsed_array[0], 1);

            $viewRender = view('admin.settings.ops-tracker.template',compact('imported_data'))->render();

            return response()->json([
                'success' => true,
                'html' => $viewRender,
                'url' => url('/').'/uploads/ops-export/'.$u_id.'/'.$file_name
            ]);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'No Data Found, Try Again with Some Other Filter Option !!'
            ]);
        }
    }

    public function progressExport(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $rules= 
        [
            'p_month' => 'required|array|min:1',
            'year' => 'required',
            'report_type' => 'required|array|in:wip,close'
            
        ];
        $custom=[
            'p_month.required' => 'Select Atleast One Month !!',
            'report_type.required' => 'Select Atleast One Report Type !!',
            'report_type.in' => 'Report Type must be in wip,close'
        ];
        $validator = Validator::make($request->all(), $rules, $custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $type = 'monthly';

        $from_date = '';

        $to_date = '';

        $month=[];

        $month = $request->p_month;
        
        // $month = $request->month;

        if($month!=null && count($month)>0)
        {
            sort($month);
        }

        $year = $request->year;

        if($request->type!=NULL && $request->type!='')
        {
            $type = $request->type;
        }

        $report_type = [];

        $report_type =  $request->report_type;

        $customer_id = $request->customer;

        $path=public_path().'/uploads/progress-export/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

       

        $query = DB::table('candidate_reinitiates as u')
                    ->DISTINCT('ri.candidate_id')
                    ->select('u.*','ub.company_name')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->join('reports as r','r.candidate_id','=','u.id')
                    ->join('report_items as ri','r.id','=','ri.report_id')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->whereIn('s.type_name',['address','employment','educational','database','judicial','reference','identity_verification','criminal'])
                    ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);
                    if($customer_id!=NULL && count($customer_id)>0)
                    {
                        $query->whereIn('u.business_id',$customer_id);
                    }
                    if($report_type!=NULL && count($report_type)>0)
                    {
                        if(count($report_type) < 2)
                        {
                            if(stripos($report_type[0],'wip')!==false)
                            {
                                $query->whereIN('r.status',['interim','incomplete']);
                            }
                            else if(stripos($report_type[0],'close')!==false){

                                $query->whereIN('r.status',['completed']);
                            }
                        }
                    }

                    if($type!='')
                    {
                        if(stripos($type,'daily')!==false)
                        {
                            $from_date = date('Y-m-d');

                            $to_date = date('Y-m-d');

                            $query=$query->whereDate('u.created_at',date('Y-m-d'));
                        }
                        else if(stripos($type,'weekly')!==false)
                        {
                            $from_date = date('Y-m-d',strtotime('- 6 days'));

                            $to_date = date('Y-m-d');

                            $query=$query->whereDate('u.created_at','>=',date('Y-m-d',strtotime('- 6 days')))->whereDate('u.created_at','<=',date('Y-m-d'));
                        }
                        else if(stripos($type,'monthly')!==false)
                        {
                            if($month!=null && count($month)>0)
                            {
                                $start = $month[0];

                                $end = end($month);

                                $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                                if($end==date('n') && $request->year==date('Y'))
                                {
                                    $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                                }
                                else
                                {
                                    $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                                }

                                $query=$query->whereIn(DB::raw('month(u.created_at)'),$month)->whereYear('u.created_at','=',$year);
                            }
                            else
                            {
                                $from_date = date('Y-m-d',strtotime(date('Y').'-'.date('m').'-'.'01'));

                                $to_date = date('Y-m-t',strtotime(date('Y').'-'.date('m')));

                                $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                            }
                        }
                    }
                    else
                    {
                        if($month!=null && count($month)>0)
                        {
                            $start = $month[0];

                            $end = end($month);

                            $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                            if($end==date('n') && $request->year==date('Y'))
                            {
                                $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                            }
                            else
                            {
                                $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                            }

                            $query=$query->whereIn(DB::raw('month(u.created_at)'),$month)->whereYear('u.created_at','=',$year);
                        }
                        else
                        {
                            $from_date = date('Y-m-d',strtotime(date('Y').'-'.date('m').'-'.'01'));

                            $to_date = date('Y-m-t',strtotime(date('Y').'-'.date('m')));

                            $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                        }
                        
                    }
                    
        $file_name = 'progress-tracker-'.date('YmdHis').'.xlsx';
                   
                // $query->orderBy('u.id','desc');

        $query = $query->groupBy('ri.candidate_id')->get();  

        // dd($query);

        if(count($query)>0)
        {
            Excel::store(new ProgressDataExport($type,$month,$year,$report_type,$business_id,$customer_id),'/uploads/progress-export/'.$file_name,'real_public');

            return response()->json([
                'success' => true,
                'url' => url('/').'/uploads/progress-export/'.$file_name
            ]);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'No Data Found, Try Again with Some Other Filter Option !!'
            ]);
        }


    }

    public function progressDataExport(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $user_id = Auth::user()->id;

        // $rules= 
        // [
        //     'p_month' => 'required|array|min:1',
        //     'year' => 'required',
        //     'report_type' => 'required|array|in:wip,close'
            
        // ];
        // $custom=[
        //     'p_month.required' => 'Select Atleast One Month !!',
        //     'report_type.required' => 'Select Atleast One Report Type !!',
        //     'report_type.in' => 'Report Type must be in wip,close'
        // ];
        // $validator = Validator::make($request->all(), $rules, $custom);
        
        // if ($validator->fails()){
        //     return response()->json([
        //         'success' => false,
        //         'errors' => $validator->errors()
        //     ]);
        // }
        $type = '';

        $from_date = '';

        $to_date = '';

        $month=[];
        // $month = $request->p_month;
        
        $month = $request->month;

        if($month!=null && count($month)>0)
        {
            sort($month);
        }

        $year = $request->year;

        if($request->type!=NULL && $request->type!='')
        {
            $type = $request->type;
        }

        $report_type = [];

        $report_type =  $request->report_type;

        $customer_id = $request->customer_id;

        // $path=public_path().'/uploads/progress-export/';

        // if(!File::exists($path))
        // {
        //     File::makeDirectory($path, $mode = 0777, true, true);
        // }

        // if (File::exists($path)) 
        // {
        //     File::cleanDirectory($path);
        // }

       

        $query = DB::table('users as u')
                    ->DISTINCT('ri.candidate_id')
                    ->select('u.*','ub.company_name')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->join('reports as r','r.candidate_id','=','u.id')
                    ->join('report_items as ri','r.id','=','ri.report_id')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->whereIn('s.type_name',['address','employment','educational','database','judicial','reference','identity_verification','criminal'])
                    ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);
                    if($customer_id!=NULL && count($customer_id)>0)
                    {
                        $query->whereIn('u.business_id',$customer_id);
                    }
                    if($report_type!=NULL && count($report_type)>0)
                    {
                        if(count($report_type) < 2)
                        {
                            if(stripos($report_type[0],'wip')!==false)
                            {
                                $query->whereIN('r.status',['interim','incomplete']);
                            }
                            else if(stripos($report_type[0],'close')!==false){

                                $query->whereIN('r.status',['completed']);
                            }
                        }
                    }

                    if($type!='')
                    {
                        if(stripos($type,'daily')!==false)
                        {
                            $from_date = date('Y-m-d');

                            $to_date = date('Y-m-d');

                            $query=$query->whereDate('u.created_at',date('Y-m-d'));
                        }
                        else if(stripos($type,'weekly')!==false)
                        {
                            $from_date = date('Y-m-d',strtotime('- 6 days'));

                            $to_date = date('Y-m-d');

                            $query=$query->whereDate('u.created_at','>=',date('Y-m-d',strtotime('- 6 days')))->whereDate('u.created_at','<=',date('Y-m-d'));
                        }
                        else if(stripos($type,'monthly')!==false)
                        {
                            if($month!=null && count($month)>0)
                            {
                                $start = $month[0];

                                $end = end($month);

                                $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                                if($end==date('n') && $request->year==date('Y'))
                                {
                                    $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                                }
                                else
                                {
                                    $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                                }

                                $query=$query->whereIn(DB::raw('month(u.created_at)'),$month)->whereYear('u.created_at','=',$year);
                            }
                            else
                            {
                                $from_date = date('Y-m-d',strtotime(date('Y').'-'.date('m').'-'.'01'));

                                $to_date = date('Y-m-t',strtotime(date('Y').'-'.date('m')));

                                $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                            }
                        }
                    }
                    else
                    {
                        if($month!=null && count($month)>0)
                        {
                            $start = $month[0];

                            $end = end($month);

                            $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                            if($end==date('n') && $request->year==date('Y'))
                            {
                                $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                            }
                            else
                            {
                                $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                            }

                            $query=$query->whereIn(DB::raw('month(u.created_at)'),$month)->whereYear('u.created_at','=',$year);
                        }
                        else
                        {
                            $from_date = date('Y-m-d',strtotime(date('Y').'-'.date('m').'-'.'01'));

                            $to_date = date('Y-m-t',strtotime(date('Y').'-'.date('m')));

                            $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                        }
                        
                    }
                    
                //$file_name = 'progress-tracker-('.$from_date.' - '.$to_date.')-'.date('YmdHis').'.xlsx';
                   
                // $query->orderBy('u.id','desc');

        $query = $query->groupBy('ri.candidate_id')->get();  

        // dd($query);

        if(count($query)>0)
        {
            // Excel::store(new ProgressDataExport($type,$month,$year,$report_type,$business_id,$customer_id),'/uploads/progress-export/'.$file_name,'real_public');

            // return response()->json([
            //     'success' => true,
            //     'url' => url('/').'/uploads/progress-export/'.$file_name
            // ]);

            ProgressExportLog::create([
                'business_id' => $business_id,
                'duration_type' => $type,
                'month'  => $month!=null && count($month)>0 ? implode(',',$month) : NULL,
                'year' => $year,
                'customer_id' => $customer_id!=NULL && count($customer_id)>0 ? implode(',',$customer_id) : NULL,
                'report_type' => $report_type!=NULL && count($report_type)>0 ? implode(',',$report_type) : NULL,
                'created_by' => $user_id
            ]);

            return response()->json([
                'success' => true
            ]);

        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'No Data Found, Try Again with Some Other Filter Option !!'
            ]);
        }


    }

    public function progressDashboard(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

        // $total_hrs_arr=[];

        // $raise_insuff_arr = [];

        // $clear_insuff_arr = [];

        // $jaf_assign_arr = [];

        // $jaf_completed_arr = [];

        // $verification_assign_arr = [];

        // $verification_completed_arr = [];

        $business_user = DB::table('users')->where('id',$business_id)->first();

        $today_date = date('Y-m-d');

        $type = 'monthly';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-01');

        $customer_id=NULL;

        $month=[date('m',strtotime($today_date))];

        // // $month=['01'];

        $year = '2022';

        if($request->get('type')!='')
        {
            if(stripos($request->type,'daily')!==false)
            {
                $from_date = date('Y-m-d');

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'weekly')!==false)
            {
                $from_date = date('Y-m-d',strtotime('- 6 days'));

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                if($request->month!=null)
                {
                    $month = [];

                    $month = explode(',',$request->month);

                    if(count($month)>0)
                    {
                        sort($month);

                        $start = $month[0];

                        $end = end($month);

                        $year = $request->year;

                        $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                        if($end==date('n') && $year==date('Y'))
                        {
                            $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                        }
                        else
                        {
                            $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                        }
                    }
                    else{

                        $month=[date('n',strtotime($today_date))];

                        $to_date = date('Y-m-d');

                        $from_date = date('Y-m-01');

                    }

                }
            }

            $type = $request->type;

        }

        $month_str = implode(",",$month);

        if($request->get('customer_id')!=null)
        {
            $customer_id = explode(',',$request->get('customer_id'));
        }

        $customers = DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
                    ->whereNotIn('u.id',[Auth::user()->business_id])
                    ->get();

        $user_name_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name')->all();

        array_unshift($user_name_arr,$business_user->name.' (Customer)');

        // // dd($user_name_arr);

        // $user_id_arr = DB::table('users')
        //             ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
        //             ->orderBy('name','asc')
        //             ->pluck('id')->all();

        // array_unshift($user_id_arr,$business_id);

        // if(count($user_id_arr)>0)
        // {
        //     foreach($user_id_arr as $user_id)
        //     {
        //         $total_hrs = 0;

        //         if(stripos($type,'daily')!==false || stripos($type,'weekly')!==false)
        //         {
                    
        //             $log_in_data = DB::table('login_logout_activity_logs')
        //                                     ->where('user_id',$user_id)
        //                                     ->whereNotNull('login_at')
        //                                     ->whereDate('login_at','=',$from_date)
        //                                     ->first();

        //             $log_out_data = DB::table('login_logout_activity_logs')
        //                                     ->where('user_id',$user_id)
        //                                     ->whereNotNull('last_login_activity_at')
        //                                     ->whereDate('last_login_activity_at','=',$to_date)
        //                                     ->latest()
        //                                     ->first();

        //             if($log_in_data!=NULL && $log_out_data!=NULL)
        //             {
        //                 $date1 = $log_in_data->login_at;
        //                 $date2 = $log_out_data->last_login_activity_at;

        //                 $total_hrs = Helper::get_total_hours($date1,$date2);
        //             }

        //             // dd($log_in_data);

        //             array_push($total_hrs_arr,$total_hrs);
        //         }
        //         else if(stripos($type,'monthly')!==false)
        //         {
        //             if($month!=NULL && count($month) > 0)
        //             {
        //                 foreach($month as $m)
        //                 {
        //                     $log_in_data = DB::table('login_logout_activity_logs')
        //                                     ->where('user_id',$user_id)
        //                                     ->whereNotNull('login_at')
        //                                     ->whereIn(DB::raw('month(login_at)'),[$m])
        //                                     ->whereYear('last_login_activity_at','=',$year)
        //                                     ->first();

        //                     $log_out_data = DB::table('login_logout_activity_logs')
        //                                             ->where('user_id',$user_id)
        //                                             ->whereNotNull('last_login_activity_at')
        //                                             ->whereIn(DB::raw('month(last_login_activity_at)'),[$m])
        //                                             ->whereYear('last_login_activity_at','=',$year)
        //                                             ->latest()
        //                                             ->first();

        //                     if($log_in_data!=NULL && $log_out_data!=NULL)
        //                     {
        //                         $date1 = $log_in_data->login_at;
        //                         $date2 = $log_out_data->last_login_activity_at;

        //                         $t = Helper::get_total_hours($date1,$date2);

        //                         $total_hrs = $total_hrs + $t;
        //                     }
        //                 }

        //                 array_push($total_hrs_arr,$total_hrs);
        //             }
        //             else
        //             {
        //                 $log_in_data = DB::table('login_logout_activity_logs')
        //                                     ->where('user_id',$user_id)
        //                                     ->whereNotNull('login_at')
        //                                     ->whereIn(DB::raw('month(login_at)'),[date('m')])
        //                                     ->whereYear('last_login_activity_at','=',$year)
        //                                     ->first();

        //                 $log_out_data = DB::table('login_logout_activity_logs')
        //                                             ->where('user_id',$user_id)
        //                                             ->whereNotNull('last_login_activity_at')
        //                                             ->whereIn(DB::raw('month(last_login_activity_at)'),[date('m')])
        //                                             ->whereYear('last_login_activity_at','=',$year)
        //                                             ->latest()
        //                                             ->first();

        //                 if($log_in_data!=NULL && $log_out_data!=NULL)
        //                 {
        //                     $date1 = $log_in_data->login_at;
        //                     $date2 = $log_out_data->last_login_activity_at;

        //                     $total_hrs = Helper::get_total_hours($date1,$date2);
        //                 }

        //                 array_push($total_hrs_arr,$total_hrs);
        //             }
        //         }
        //         else
        //         {
        //             $log_in_data = DB::table('login_logout_activity_logs')
        //                                     ->where('user_id',$user_id)
        //                                     ->whereNotNull('login_at')
        //                                     ->whereIn(DB::raw('month(login_at)'),[date('m')])
        //                                     ->whereYear('last_login_activity_at','=',$year)
        //                                     ->first();

        //             $log_out_data = DB::table('login_logout_activity_logs')
        //                                             ->where('user_id',$user_id)
        //                                             ->whereNotNull('last_login_activity_at')
        //                                             ->whereIn(DB::raw('month(last_login_activity_at)'),[date('m')])
        //                                             ->whereYear('last_login_activity_at','=',$year)
        //                                             ->latest()
        //                                             ->first();

        //             if($log_in_data!=NULL && $log_out_data!=NULL)
        //             {
        //                 $date1 = $log_in_data->login_at;
        //                 $date2 = $log_out_data->last_login_activity_at;

        //                 $total_hrs = Helper::get_total_hours($date1,$date2);
        //             }

        //             array_push($total_hrs_arr,$total_hrs);
        //         }

        //         // JAF Filling Task

        //             $jaf_assign_task =DB::table('tasks as t')
        //                         ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        //                         ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        //                         ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        //                         ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'1'])
        //                         ->whereNotNull('t.assigned_to')
        //                         ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
        //                         if($customer_id!=NULL && count($customer_id)>0)
        //                         {
        //                             $jaf_assign_task->whereIn('t.business_id',$customer_id);
        //                         }
        //                         if(stripos($type,'daily')!==false)
        //                         {
        //                             $jaf_assign_task->whereDate('t.start_date',$from_date);
        //                         }
        //                         else if(stripos($type,'weekly')!==false)
        //                         {
        //                             $jaf_assign_task->whereDate('t.start_date','>=',$from_date)->whereDate('t.start_date','<=',$to_date);
        //                         }
        //                         else if(stripos($type,'monthly')!==false)
        //                         {
        //                             if($month!=NULL && count($month) > 0)
        //                             {
        //                                 $jaf_assign_task->whereIn(DB::raw('month(t.start_date)'),$month)->whereYear('t.start_date','=',$year);
        //                             }
        //                             else
        //                             {
        //                                 $jaf_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
        //                             }
        //                         }
        //                         else
        //                         {
        //                             $jaf_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
        //                         }

        //             $jaf_assign_task=$jaf_assign_task->get();

        //             // dd(DB::getQueryLog());

        //             array_push($jaf_assign_arr,count($jaf_assign_task));

        //             // JAF Filling Completed

        //              // DB::enableQueryLog();

        //              $jaf_complete_task =DB::table('tasks as t')
        //              ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        //              ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        //              ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        //              ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'2'])
        //              ->whereNotNull('t.assigned_to')
        //              ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
        //              if($customer_id!=NULL && count($customer_id)>0)
        //              {
        //                  $jaf_complete_task->whereIn('t.business_id',$customer_id);
        //              }
        //              if(stripos($type,'daily')!==false)
        //              {
        //                  $jaf_complete_task->whereDate('t.updated_at',$from_date);
        //              }
        //              else if(stripos($type,'weekly')!==false)
        //              {
        //                  $jaf_complete_task->whereDate('t.updated_at','>=',$from_date)->whereDate('t.updated_at','<=',$to_date);
        //              }
        //              else if(stripos($type,'monthly')!==false)
        //              {
        //                  if($month!=NULL && count($month)>0)
        //                  {
        //                     $jaf_complete_task->whereIn(DB::raw('month(t.updated_at)'),$month)->whereYear('t.updated_at','=',$year);
        //                  }
        //                  else
        //                  {
        //                     $jaf_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
        //                  }
        //              }
        //              else
        //              {
        //                  $jaf_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
        //              }

        //             $jaf_complete_task=$jaf_complete_task->get();

        //             // dd(DB::getQueryLog());

        //             array_push($jaf_completed_arr,count($jaf_complete_task));

        //                 // Verification Task 

        //                     // Verification Task Assign

        //                     // DB::enableQueryLog();

        //                     $ver_assign_task =DB::table('tasks as t')
        //                     ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        //                     ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        //                     ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        //                     ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
        //                     ->whereNotNull('t.assigned_to')
        //                     ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
        //                     if($customer_id!=NULL && count($customer_id)>0)
        //                     {
        //                         $ver_assign_task->whereIn('t.business_id',$customer_id);
        //                     }
        //                     if(stripos($type,'daily')!==false)
        //                     {
        //                         $ver_assign_task->whereDate('t.start_date',$from_date);
        //                     }
        //                     else if(stripos($type,'weekly')!==false)
        //                     {
        //                         $ver_assign_task->whereDate('t.start_date','>=',$from_date)->whereDate('t.start_date','<=',$to_date);
        //                     }
        //                     else if(stripos($type,'monthly')!==false)
        //                     {
        //                         if($month!=NULL && count($month)>0)
        //                         {
        //                             $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),$month)->whereYear('t.start_date','=',$year);
        //                         }
        //                         else
        //                         {
        //                             $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
        //                         }
        //                     }
        //                     else
        //                     {
        //                         $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
        //                     }

        //         $ver_assign_task=$ver_assign_task->get();

        //         // dd(DB::getQueryLog());

        //         array_push($verification_assign_arr,count($ver_assign_task));


        //         // Verification Task Completed

        //                     // DB::enableQueryLog();

        //                     $ver_complete_task =DB::table('tasks as t')
        //                     ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        //                     ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        //                     ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        //                     ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'2'])
        //                     ->whereNotNull('t.assigned_to')
        //                     ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
        //                     if($customer_id!=NULL && count($customer_id)>0)
        //                     {
        //                         $ver_complete_task->whereIn('t.business_id',$customer_id);
        //                     }
        //                     if(stripos($type,'daily')!==false)
        //                     {
        //                         $ver_complete_task->whereDate('t.updated_at',$from_date);
        //                     }
        //                     else if(stripos($type,'weekly')!==false)
        //                     {
        //                         $ver_complete_task->whereDate('t.updated_at','>=',$from_date)->whereDate('t.updated_at','<=',$to_date);
        //                     }
        //                     else if(stripos($type,'monthly')!==false)
        //                     {
        //                         if($month!=NULL && count($month)>0)
        //                         {
        //                             $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),$month)->whereYear('t.updated_at','=',$year);
        //                         }
        //                         else
        //                         {
        //                             $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
        //                         }
        //                     }
        //                     else
        //                     {
        //                         $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
        //                     }

        //         $ver_complete_task=$ver_complete_task->get();

        //         // dd(DB::getQueryLog());

        //         array_push($verification_completed_arr,count($ver_complete_task));

        //         // Raise Insuff

        //         // DB::enableQueryLog();

        //         $raise_insuff = DB::table('verification_insufficiency as v')
        //             ->select('v.*')
        //             ->join('jaf_form_data as jd','jd.id','=','v.jaf_form_data_id')
        //             ->join('job_items as j','j.id','=','jd.job_item_id')
        //             ->whereNotNull('v.created_by')
        //             ->whereIn('v.status',['raised','failed']);
        //             if($customer_id!=NULL && count($customer_id)>0)
        //             {
        //                 $raise_insuff->whereIn('jd.business_id',$customer_id);
        //             }
        //             if(stripos($type,'daily')!==false)
        //             {
        //                 $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) = "'.$from_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) = "'.$from_date.'" AND v.created_by='.$user_id.')');
        //             }
        //             else if(stripos($type,'weekly')!==false)
        //             {
        //                 $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) >= "'.$from_date.'" AND DATE(v.updated_at) <= "'.$to_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) >= "'.$from_date.'" AND DATE(v.created_at) <= "'.$to_date.'" AND v.created_by='.$user_id.')');
        //             }
        //             else if(stripos($type,'monthly')!==false)
        //             {
        //                 $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.$month_str.') AND year(v.updated_at) = "'.$year.'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.$month_str.') AND year(v.created_at) = "'.$year.'" AND v.created_by='.$user_id.')');
        //             }
        //             else
        //             {
        //                 $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.date('m').') AND year(v.updated_at) = "'.date('Y').'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.date('m').') AND year(v.created_at) = "'.date('Y').'" AND v.created_by='.$user_id.')');
        //             }

        //             $raise_insuff->where(['j.jaf_status'=>'filled']);

        //             $raise_insuff=$raise_insuff->get();

        //         // dd(DB::getQueryLog());
                
        //         array_push($raise_insuff_arr,count($raise_insuff));


        //         // Clear Insuff
                
        //         $clear_insuff = DB::table('verification_insufficiency as v')
        //                         ->select('v.*')
        //                         ->join('jaf_form_data as jd','jd.id','=','v.jaf_form_data_id')
        //                         ->join('job_items as j','j.id','=','jd.job_item_id')
        //                         ->join('services as s','s.id','=','v.service_id')
        //                         ->where(['j.jaf_status'=>'filled'])
        //                         ->whereNotNull('v.created_by')
        //                         ->whereIn('v.status',['removed']);
        //                         if($customer_id!=NULL && count($customer_id)>0)
        //                         {
        //                             $clear_insuff->whereIn('jd.business_id',$customer_id);
        //                         }
        //                         if(stripos($type,'daily')!==false)
        //                         {
        //                             $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) = "'.$from_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) = "'.$from_date.'" AND v.created_by='.$user_id.')');
        //                         }
        //                         else if(stripos($type,'weekly')!==false)
        //                         {
        //                             $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) >= "'.$from_date.'" AND DATE(v.updated_at) <= "'.$to_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) >= "'.$from_date.'" AND DATE(v.created_at) <= "'.$to_date.'" AND v.created_by='.$user_id.')');
        //                         }
        //                         else if(stripos($type,'monthly')!==false)
        //                         {
        //                             $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.$month_str.') AND year(v.updated_at) = "'.$year.'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.$month_str.') AND year(v.created_at) = "'.$year.'" AND v.created_by='.$user_id.')');
        //                         }
        //                         else
        //                         {
        //                             $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.date('m').') AND year(v.updated_at) = "'.date('Y').'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.date('m').') AND year(v.created_at) = "'.date('Y').'" AND v.created_by='.$user_id.')');
        //                         }

        //                 $clear_insuff=$clear_insuff->get();

        //                 array_push($clear_insuff_arr,count($clear_insuff));
        //     }
        // }

        // dd($total_hrs_arr);

         //dd($verification_assign_arr);

        if($request->ajax())
        {
            return view('admin.settings.progress-tracker.ajax',compact('type','from_date','to_date','customer_id','month','customers','user_name_arr'));
        }
        else
        {
            return view('admin.settings.progress-tracker.index',compact('type','from_date','to_date','customer_id','month','customers','user_name_arr'));
        }

    }

    public function progressPreview(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

        $type = '';

        $from_date = '';

        $to_date = '';

        $month=[];
        // $month = $request->p_month;
        
        $month = $request->month;

        if($month!=null && count($month)>0)
        {
            sort($month);
        }

        $year = $request->year;

        if($request->type!=NULL && $request->type!='')
        {
            $type = $request->type;
        }

        $report_type = [];

        $report_type =  $request->report_type;

        $customer_id = $request->customer_id;

        $query = DB::table('candidate_reinitiates as u')
                    ->DISTINCT('ri.candidate_id')
                    ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','r.status as report_status','r.report_complete_created_at as case_completed_date','j.price_type','j.package_price','r.is_report_complete')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->join('reports as r','r.candidate_id','=','u.id')
                    ->join('report_items as ri','r.id','=','ri.report_id')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->whereIn('s.type_name',['address','employment','educational','database','judicial','reference','identity_verification','criminal'])
                    ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);
                    if($customer_id!=NULL && count($customer_id)>0)
                    {
                        $query->whereIn('u.business_id',$customer_id);
                    }
                    if($report_type!=NULL && count($report_type)>0)
                    {
                        if(count($report_type) < 2)
                        {
                            if(stripos($report_type[0],'wip')!==false)
                            {
                                $query->whereIN('r.status',['interim','incomplete']);
                            }
                            else if(stripos($report_type[0],'close')!==false){

                                $query->whereIN('r.status',['completed']);
                            }
                        }
                    }

                    if($type!='')
                    {
                        if(stripos($type,'daily')!==false)
                        {
                            $from_date = date('Y-m-d');

                            $to_date = date('Y-m-d');

                            $query=$query->whereDate('u.created_at',date('Y-m-d'));
                        }
                        else if(stripos($type,'weekly')!==false)
                        {
                            $from_date = date('Y-m-d',strtotime('- 6 days'));

                            $to_date = date('Y-m-d');

                            $query=$query->whereDate('u.created_at','>=',date('Y-m-d',strtotime('- 6 days')))->whereDate('u.created_at','<=',date('Y-m-d'));
                        }
                        else if(stripos($type,'monthly')!==false)
                        {
                            if($month!=null && count($month)>0)
                            {
                                $start = $month[0];

                                $end = end($month);

                                $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                                if($end==date('n') && $request->year==date('Y'))
                                {
                                    $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                                }
                                else
                                {
                                    $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                                }

                                $query=$query->whereIn(DB::raw('month(u.created_at)'),$month)->whereYear('u.created_at','=',$year);
                            }
                            else
                            {
                                $from_date = date('Y-m-d',strtotime(date('Y').'-'.date('m').'-'.'01'));

                                $to_date = date('Y-m-t',strtotime(date('Y').'-'.date('m')));

                                $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                            }
                        }
                    }
                    else
                    {
                        if($month!=null && count($month)>0)
                        {
                            $start = $month[0];

                            $end = end($month);

                            $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                            if($end==date('n') && $request->year==date('Y'))
                            {
                                $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                            }
                            else
                            {
                                $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                            }

                            $query=$query->whereIn(DB::raw('month(u.created_at)'),$month)->whereYear('u.created_at','=',$year);
                        }
                        else
                        {
                            $from_date = date('Y-m-d',strtotime(date('Y').'-'.date('m').'-'.'01'));

                            $to_date = date('Y-m-t',strtotime(date('Y').'-'.date('m')));

                            $query=$query->whereIn(DB::raw('month(u.created_at)'),[date('m')])->whereYear('u.created_at','=',date('Y'));
                        }
                        
                    }

        $progress_list = $query->groupBy('ri.candidate_id')->get();  

        if(count($progress_list)>0)
        {
            $viewRender = view('admin.settings.progress-tracker.template',compact('progress_list','type','month','year','report_type','business_id','customer_id'))->render();

            return response()->json([
                'success' => true,
                'html' => $viewRender
            ]);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'No Data Found, Try Again with Some Other Filter Option !!'
            ]);
        }


    }

    public function totalHoursCard(Request $request)
    {
        // dd($request->all());
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

        $total_hrs_arr=[];

        $business_user = DB::table('users')->where('id',$business_id)->first();

        $today_date = date('Y-m-d');

        $type = 'monthly';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-01');

        $customer_id=NULL;

        $month=[date('m',strtotime($today_date))];

        // $month=['01'];

        $year = '2022';
        if($request->get('type')!='')
        {
            if(stripos($request->type,'daily')!==false)
            {
                $from_date = date('Y-m-d');

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'weekly')!==false)
            {
                $from_date = date('Y-m-d',strtotime('- 6 days'));

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                if($request->month!=null)
                {
                    $month = $request->month;
                    // dd($request->month);
                    // $month = explode(',',$request->month);

                    if(count($month)>0)
                    {
                        sort($month);

                        $start = $month[0];
                        
                        $end = end($month);
                        // dd($end);
                        $year = $request->year;

                        $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                        if($end==date('n') && $year==date('Y'))
                        {
                            $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                        }
                        else
                        {
                            $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                        }
                    }
                    else{

                        $month=[date('n',strtotime($today_date))];

                        $to_date = date('Y-m-d');

                        $from_date = date('Y-m-01');

                    }

                }
            }

            $type = $request->type;

        }
            //    $month_str = implode(",",$month);
                // dd($month_str);
        if($request->get('customer_id')!=null)
        {
            $customer_id = $request->get('customer_id');
        }

        $customers = DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
                    ->whereNotIn('u.id',[Auth::user()->business_id])
                    ->get();

        $user_name_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name')->all();

        array_unshift($user_name_arr,$business_user->name.' (Customer)');

        // dd($user_name_arr);

        $user_id_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('id')->all();

        array_unshift($user_id_arr,$business_id);
        if(count($user_id_arr)>0)
        {
            foreach($user_id_arr as $user_id)
            {
                $total_hrs = 0;

                if(stripos($type,'daily')!==false || stripos($type,'weekly')!==false)
                {
                    
                    $log_in_data = DB::table('login_logout_activity_logs')
                                            ->where('user_id',$user_id)
                                            ->whereNotNull('login_at')
                                            ->whereDate('login_at','=',$from_date)
                                            ->first();

                    $log_out_data = DB::table('login_logout_activity_logs')
                                            ->where('user_id',$user_id)
                                            ->whereNotNull('last_login_activity_at')
                                            ->whereDate('last_login_activity_at','=',$to_date)
                                            ->latest()
                                            ->first();

                    if($log_in_data!=NULL && $log_out_data!=NULL)
                    {
                        $date1 = $log_in_data->login_at;
                        $date2 = $log_out_data->last_login_activity_at;

                        $total_hrs = Helper::get_total_hours($date1,$date2);
                    }

                    // dd($log_in_data);

                    array_push($total_hrs_arr,$total_hrs);
                }
                else if(stripos($type,'monthly')!==false)
                {
                    if($month!=NULL && count($month) > 0)
                    {
                        foreach($month as $m)
                        {
                            $log_in_data = DB::table('login_logout_activity_logs')
                                            ->where('user_id',$user_id)
                                            ->whereNotNull('login_at')
                                            ->whereIn(DB::raw('month(login_at)'),[$m])
                                            ->whereYear('last_login_activity_at','=',$year)
                                            ->first();

                            $log_out_data = DB::table('login_logout_activity_logs')
                                                    ->where('user_id',$user_id)
                                                    ->whereNotNull('last_login_activity_at')
                                                    ->whereIn(DB::raw('month(last_login_activity_at)'),[$m])
                                                    ->whereYear('last_login_activity_at','=',$year)
                                                    ->latest()
                                                    ->first();

                            if($log_in_data!=NULL && $log_out_data!=NULL)
                            {
                                $date1 = $log_in_data->login_at;
                                $date2 = $log_out_data->last_login_activity_at;

                                $t = Helper::get_total_hours($date1,$date2);

                                $total_hrs = $total_hrs + $t;
                            }
                        }

                        array_push($total_hrs_arr,$total_hrs);
                    }
                    else
                    {
                        $log_in_data = DB::table('login_logout_activity_logs')
                                            ->where('user_id',$user_id)
                                            ->whereNotNull('login_at')
                                            ->whereIn(DB::raw('month(login_at)'),[date('m')])
                                            ->whereYear('last_login_activity_at','=',$year)
                                            ->first();

                        $log_out_data = DB::table('login_logout_activity_logs')
                                                    ->where('user_id',$user_id)
                                                    ->whereNotNull('last_login_activity_at')
                                                    ->whereIn(DB::raw('month(last_login_activity_at)'),[date('m')])
                                                    ->whereYear('last_login_activity_at','=',$year)
                                                    ->latest()
                                                    ->first();

                        if($log_in_data!=NULL && $log_out_data!=NULL)
                        {
                            $date1 = $log_in_data->login_at;
                            $date2 = $log_out_data->last_login_activity_at;

                            $total_hrs = Helper::get_total_hours($date1,$date2);
                        }

                        array_push($total_hrs_arr,$total_hrs);
                    }
                }
                else
                {
                    $log_in_data = DB::table('login_logout_activity_logs')
                                            ->where('user_id',$user_id)
                                            ->whereNotNull('login_at')
                                            ->whereIn(DB::raw('month(login_at)'),[date('m')])
                                            ->whereYear('last_login_activity_at','=',$year)
                                            ->first();

                    $log_out_data = DB::table('login_logout_activity_logs')
                                                    ->where('user_id',$user_id)
                                                    ->whereNotNull('last_login_activity_at')
                                                    ->whereIn(DB::raw('month(last_login_activity_at)'),[date('m')])
                                                    ->whereYear('last_login_activity_at','=',$year)
                                                    ->latest()
                                                    ->first();

                    if($log_in_data!=NULL && $log_out_data!=NULL)
                    {
                        $date1 = $log_in_data->login_at;
                        $date2 = $log_out_data->last_login_activity_at;

                        $total_hrs = Helper::get_total_hours($date1,$date2);
                    }

                    array_push($total_hrs_arr,$total_hrs);
                }
            }
        }
        $viewRender = view('admin.settings.progress-tracker.total_login_hours_card',compact('type','from_date','to_date','customer_id','month','customers','user_name_arr','total_hrs_arr'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));

    }
    public function jafTaskCard(Request $request)
    {
        // dd($request->all());
         $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;

        $jaf_assign_arr = [];

        $jaf_completed_arr = [];

        $business_user = DB::table('users')->where('id',$business_id)->first();

        $today_date = date('Y-m-d');

        $type = 'monthly';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-01');

        $customer_id=NULL;

        $month=[date('m',strtotime($today_date))];

        // $month=['01'];

        $year = '2022';
        if($request->get('type')!='')
        {
            if(stripos($request->type,'daily')!==false)
            {
                $from_date = date('Y-m-d');

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'weekly')!==false)
            {
                $from_date = date('Y-m-d',strtotime('- 6 days'));

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                if($request->month!=null)
                {
                    $month = $request->month;

                    // $month = explode(',',$request->month);

                    if(count($month)>0)
                    {
                        sort($month);
                        
                        $start = $month[0];
                        
                        $end = end($month);

                        $year = $request->year;

                        $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                        if($end==date('n') && $year==date('Y'))
                        {
                            $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                        }
                        else
                        {
                            $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                        }
                    }
                    else{

                        $month=[date('n',strtotime($today_date))];

                        $to_date = date('Y-m-d');

                        $from_date = date('Y-m-01');

                    }

                }
            }

            $type = $request->type;

        }
        // $month_str = implode(",",$month);

        if($request->get('customer_id')!=null)
        {
            $customer_id = $request->get('customer_id');
        }

        $customers = DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
                    ->whereNotIn('u.id',[Auth::user()->business_id])
                    ->get();

        $user_name_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name')->all();

        array_unshift($user_name_arr,$business_user->name.' (Customer)');

        // dd($user_name_arr);

        $user_id_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('id')->all();

        array_unshift($user_id_arr,$business_id);
        if(count($user_id_arr)>0)
        {
            foreach($user_id_arr as $user_id)
            {
                // JAF Filling Task
                // DB::enableQueryLog();
                $jaf_assign_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'1'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($customer_id!=NULL && count($customer_id)>0)
                {
                    $jaf_assign_task->whereIn('t.business_id',$customer_id);
                }
                if(stripos($type,'daily')!==false)
                {
                    $jaf_assign_task->whereDate('t.start_date',$from_date);
                }
                else if(stripos($type,'weekly')!==false)
                {
                    $jaf_assign_task->whereDate('t.start_date','>=',$from_date)->whereDate('t.start_date','<=',$to_date);
                }
                else if(stripos($type,'monthly')!==false)
                {
                    if($month!=NULL && count($month) > 0)
                    {
                        // dd($month);
                        $jaf_assign_task->whereIn(DB::raw('month(t.start_date)'),$month)->whereYear('t.start_date','=',$year);
                    }
                    else
                    {
                        $jaf_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                    }
                }
                else
                {
                    $jaf_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                }

                $jaf_assign_task=$jaf_assign_task->get();
                // dd($jaf_assign_task);
                // dd(DB::getQueryLog());

                array_push($jaf_assign_arr,count($jaf_assign_task));

                // JAF Filling Completed

                // DB::enableQueryLog();

                $jaf_complete_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'JAF Filling','ta.status'=>'2'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($customer_id!=NULL && count($customer_id)>0)
                {
                    $jaf_complete_task->whereIn('t.business_id',$customer_id);
                }
                if(stripos($type,'daily')!==false)
                {
                    $jaf_complete_task->whereDate('t.updated_at',$from_date);
                }
                else if(stripos($type,'weekly')!==false)
                {
                    $jaf_complete_task->whereDate('t.updated_at','>=',$from_date)->whereDate('t.updated_at','<=',$to_date);
                }
                else if(stripos($type,'monthly')!==false)
                {
                    if($month!=NULL && count($month)>0)
                    {
                        $jaf_complete_task->whereIn(DB::raw('month(t.updated_at)'),$month)->whereYear('t.updated_at','=',$year);
                    }
                    else
                    {
                        $jaf_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
                    }
                }
                else
                {
                    $jaf_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
                }

                $jaf_complete_task=$jaf_complete_task->get();
                // dd( $jaf_complete_task);
                // dd(DB::getQueryLog());

                array_push($jaf_completed_arr,count($jaf_complete_task));
            }
        }
        $viewRender = view('admin.settings.progress-tracker.jaf_task_card',compact('jaf_assign_arr','jaf_completed_arr'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
        
    }
    public function verificationTaskCard(Request $request)
    {
         $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;
        $total_hrs_arr=[];

        $raise_insuff_arr = [];

        $clear_insuff_arr = [];

        $jaf_assign_arr = [];

        $jaf_completed_arr = [];

        $verification_assign_arr = [];

        $verification_completed_arr = [];

        $business_user = DB::table('users')->where('id',$business_id)->first();

        $today_date = date('Y-m-d');

        $type = 'monthly';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-01');

        $customer_id=NULL;

        $month=[date('m',strtotime($today_date))];

        // $month=['01'];

        $year = '2022';
        if($request->get('type')!='')
        {
            if(stripos($request->type,'daily')!==false)
            {
                $from_date = date('Y-m-d');

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'weekly')!==false)
            {
                $from_date = date('Y-m-d',strtotime('- 6 days'));

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                if($request->month!=null)
                {
                    $month = $request->month;;

                    // $month = explode(',',$request->month);

                    if(count($month)>0)
                    {
                        sort($month);

                        $start = $month[0];

                        $end = end($month);

                        $year = $request->year;

                        $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                        if($end==date('n') && $year==date('Y'))
                        {
                            $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                        }
                        else
                        {
                            $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                        }
                    }
                    else{

                        $month=[date('n',strtotime($today_date))];

                        $to_date = date('Y-m-d');

                        $from_date = date('Y-m-01');

                    }

                }
            }

            $type = $request->type;

        }
        // $month_str = implode(",",$month);

        if($request->get('customer_id')!=null)
        {
            $customer_id = $request->get('customer_id');
        }

        $customers = DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
                    ->whereNotIn('u.id',[Auth::user()->business_id])
                    ->get();

        $user_name_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name')->all();

        array_unshift($user_name_arr,$business_user->name.' (Customer)');

        // dd($user_name_arr);

        $user_id_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('id')->all();

        array_unshift($user_id_arr,$business_id);
        if(count($user_id_arr)>0)
        {
            foreach($user_id_arr as $user_id)
            {
                // Verification Task 

                // Verification Task Assign

                // DB::enableQueryLog();

                $ver_assign_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($customer_id!=NULL && count($customer_id)>0)
                {
                    $ver_assign_task->whereIn('t.business_id',$customer_id);
                }
                if(stripos($type,'daily')!==false)
                {
                    $ver_assign_task->whereDate('t.start_date',$from_date);
                }
                else if(stripos($type,'weekly')!==false)
                {
                    $ver_assign_task->whereDate('t.start_date','>=',$from_date)->whereDate('t.start_date','<=',$to_date);
                }
                else if(stripos($type,'monthly')!==false)
                {
                    if($month!=NULL && count($month)>0)
                    {
                        $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),$month)->whereYear('t.start_date','=',$year);
                    }
                    else
                    {
                        $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                    }
                }
                else
                {
                    $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                }

                $ver_assign_task=$ver_assign_task->get();

                // dd(DB::getQueryLog());

                array_push($verification_assign_arr,count($ver_assign_task));


                // Verification Task Completed

                            // DB::enableQueryLog();

                            $ver_complete_task =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'2'])
                            ->whereNotNull('t.assigned_to')
                            ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                            if($customer_id!=NULL && count($customer_id)>0)
                            {
                                $ver_complete_task->whereIn('t.business_id',$customer_id);
                            }
                            if(stripos($type,'daily')!==false)
                            {
                                $ver_complete_task->whereDate('t.updated_at',$from_date);
                            }
                            else if(stripos($type,'weekly')!==false)
                            {
                                $ver_complete_task->whereDate('t.updated_at','>=',$from_date)->whereDate('t.updated_at','<=',$to_date);
                            }
                            else if(stripos($type,'monthly')!==false)
                            {
                                if($month!=NULL && count($month)>0)
                                {
                                    $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),$month)->whereYear('t.updated_at','=',$year);
                                }
                                else
                                {
                                    $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
                                }
                            }
                            else
                            {
                                $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
                            }

                $ver_complete_task=$ver_complete_task->get();

                // dd(DB::getQueryLog());

                array_push($verification_completed_arr,count($ver_complete_task));
            }
        }
        $viewRender = view('admin.settings.progress-tracker.verification_task_card',compact('type','from_date','to_date','customer_id','month','customers','user_name_arr','verification_assign_arr','verification_completed_arr'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
        
    }
    public function insuffCard(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;
        $total_hrs_arr=[];

        $raise_insuff_arr = [];

        $clear_insuff_arr = [];

        $jaf_assign_arr = [];

        $jaf_completed_arr = [];

        $verification_assign_arr = [];

        $verification_completed_arr = [];

        $business_user = DB::table('users')->where('id',$business_id)->first();

        $today_date = date('Y-m-d');

        $type = 'monthly';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-01');

        $customer_id=NULL;

        $month=[date('m',strtotime($today_date))];

        // $month=['01'];

        $year = '2022';
        if($request->get('type')!='')
        {
            if(stripos($request->type,'daily')!==false)
            {
                $from_date = date('Y-m-d');

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'weekly')!==false)
            {
                $from_date = date('Y-m-d',strtotime('- 6 days'));

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                if($request->month!=null)
                {
                    $month = $request->month;

                    // $month = explode(',',$request->month);

                    if(count($month)>0)
                    {
                        sort($month);
                        // dd($month);
                        $start = $month[0];

                        $end = end($month);

                        $year = $request->year;

                        $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                        if($end==date('n') && $year==date('Y'))
                        {
                            $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                        }
                        else
                        {
                            $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                        }
                    }
                    else{

                        $month=[date('n',strtotime($today_date))];

                        $to_date = date('Y-m-d');

                        $from_date = date('Y-m-01');

                    }

                }
            }

            $type = $request->type;

        }
       $month_str = implode(",",$month);

        if($request->get('customer_id')!=null)
        {
            $customer_id = $request->get('customer_id');
        }

        $customers = DB::table('users as u')
                    ->select('u.*','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['user_type'=>'client','parent_id'=>Auth::user()->business_id])
                    ->whereNotIn('u.id',[Auth::user()->business_id])
                    ->get();

        $user_name_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('name')->all();

        array_unshift($user_name_arr,$business_user->name.' (Customer)');

        // dd($user_name_arr);

        $user_id_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('id')->all();

        array_unshift($user_id_arr,$business_id);
        if(count($user_id_arr)>0)
        {
            foreach($user_id_arr as $user_id)
            {
                // Raise Insuff

                // DB::enableQueryLog();

                $raise_insuff = DB::table('verification_insufficiency as v')
                    ->select('v.*')
                    ->join('jaf_form_data as jd','jd.id','=','v.jaf_form_data_id')
                    ->join('job_items as j','j.id','=','jd.job_item_id')
                    ->whereNotNull('v.created_by')
                    ->whereIn('v.status',['raised','failed']);
                    if($customer_id!=NULL && count($customer_id)>0)
                    {
                        $raise_insuff->whereIn('jd.business_id',$customer_id);
                    }
                    if(stripos($type,'daily')!==false)
                    {
                        $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) = "'.$from_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) = "'.$from_date.'" AND v.created_by='.$user_id.')');
                    }
                    else if(stripos($type,'weekly')!==false)
                    {
                        $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) >= "'.$from_date.'" AND DATE(v.updated_at) <= "'.$to_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) >= "'.$from_date.'" AND DATE(v.created_at) <= "'.$to_date.'" AND v.created_by='.$user_id.')');
                    }
                    else if(stripos($type,'monthly')!==false)
                    {
                        $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.$month_str.') AND year(v.updated_at) = "'.$year.'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.$month_str.') AND year(v.created_at) = "'.$year.'" AND v.created_by='.$user_id.')');
                    }
                    else
                    {
                        $raise_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.date('m').') AND year(v.updated_at) = "'.date('Y').'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.date('m').') AND year(v.created_at) = "'.date('Y').'" AND v.created_by='.$user_id.')');
                    }

                    $raise_insuff->where(['j.jaf_status'=>'filled']);

                    $raise_insuff=$raise_insuff->get();

                // dd(DB::getQueryLog());
                
                array_push($raise_insuff_arr,count($raise_insuff));


                // Clear Insuff
                
                $clear_insuff = DB::table('verification_insufficiency as v')
                                ->select('v.*')
                                ->join('jaf_form_data as jd','jd.id','=','v.jaf_form_data_id')
                                ->join('job_items as j','j.id','=','jd.job_item_id')
                                ->join('services as s','s.id','=','v.service_id')
                                ->where(['j.jaf_status'=>'filled'])
                                ->whereNotNull('v.created_by')
                                ->whereIn('v.status',['removed']);
                                if($customer_id!=NULL && count($customer_id)>0)
                                {
                                    $clear_insuff->whereIn('jd.business_id',$customer_id);
                                }
                                if(stripos($type,'daily')!==false)
                                {
                                    $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) = "'.$from_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) = "'.$from_date.'" AND v.created_by='.$user_id.')');
                                }
                                else if(stripos($type,'weekly')!==false)
                                {
                                    $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, DATE(v.updated_at) >= "'.$from_date.'" AND DATE(v.updated_at) <= "'.$to_date.'" AND v.updated_by='.$user_id.', DATE(v.created_at) >= "'.$from_date.'" AND DATE(v.created_at) <= "'.$to_date.'" AND v.created_by='.$user_id.')');
                                }
                                else if(stripos($type,'monthly')!==false)
                                {
                                    $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.$month_str.') AND year(v.updated_at) = "'.$year.'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.$month_str.') AND year(v.created_at) = "'.$year.'" AND v.created_by='.$user_id.')');
                                }
                                else
                                {
                                    $clear_insuff->whereRaw('IF (v.updated_by IS NOT NULL, month(v.updated_at) IN('.date('m').') AND year(v.updated_at) = "'.date('Y').'" AND v.updated_by='.$user_id.', month(v.created_at) IN('.date('m').') AND year(v.created_at) = "'.date('Y').'" AND v.created_by='.$user_id.')');
                                }

                        $clear_insuff=$clear_insuff->get();
                                // dd($clear_insuff);
                        array_push($clear_insuff_arr,count($clear_insuff));
            }
        }
        $viewRender = view('admin.settings.progress-tracker.insuff_card',compact('type','from_date','to_date','customer_id','month','customers','user_name_arr','raise_insuff_arr','clear_insuff_arr'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
        
    }
    public function dailyExcelReport(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $customers = DB::table('users')
                        ->where('user_type','customer')
                        ->get();

        if(count($customers)>0)
        {
            foreach($customers as $cust)
            {
                $business_id = $cust->id;

                $candidates = DB::table('candidate_reinitiates')
                                ->where(['parent_id'=>$business_id,'user_type'=>'candidate','is_deleted'=>0])
                                ->get();

                if(count($candidates) > 0)
                {   
                    $today_date = date('Y-m-d');

                    $path=public_path().'/uploads/daily-data-export/';
    
                    $file_name = 'daily-report-'.date('YmdHis').'.xlsx';
    
                    if(!File::exists($path))
                    {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }
    
                    if (File::exists($path)) 
                    {
                        File::cleanDirectory($path);
                    }
    
                    //Excel::download(new DailyDataExport($today_date,$business_id),$file_name);

                    $previous_date = date('Y-m-d',strtotime($today_date.'-1 day'));

                    // Receiving Cases Yesterday

                    $receive_amount = 0.00;
                
                    $receiving_case = DB::table('candidate_reinitiates')
                                        ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
                                        ->whereDate('created_at',$previous_date)
                                        ->count();

                    $case_detail = DB::table('candidate_reinitiates as u')
                                    ->select('u.parent_id','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type','u.id as candidate_id','j.price_type','j.package_price')
                                    ->join('job_items as j','j.candidate_id','=','u.id')
                                    ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate'])
                                    ->whereDate('u.created_at',$previous_date)
                                    ->get();

                    if(count($case_detail) > 0)
                    {
                        foreach($case_detail as $item)
                        {
                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $receive_amount = number_format(str_replace(',','',number_format($receive_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $receive_amount = number_format(str_replace(',','',number_format($receive_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $receive_amount = number_format(str_replace(',','',number_format($receive_amount + $job_sla_item_price,2)),2,".","");
                            }
                        }
                    }

                    // FR Delivered Yesterday

                    $report_delivered = DB::table('reports as r')
                                        ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                        ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','r.is_report_complete'=>1])
                                        ->whereIn('r.status',['interim','completed'])
                                        ->whereDate('r.report_complete_created_at',$previous_date)
                                        ->count();

                    // FR Yesterday IN & Out TAT

                    $case_detail = DB::table('reports as r')
                    ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','r.is_report_complete'=>1])
                    ->whereIn('r.status',['interim','completed'])
                    ->whereDate('r.report_complete_created_at',$previous_date)
                    ->get();

                    $fr_in_tat_y = 0;

                    $fr_out_tat_y = 0;

                    $fr_total_amount = 0.00;

                    if(count($case_detail) > 0)
                    {
                        foreach($case_detail as $item)
                        {
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($item->report_complete_created_at));

                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $fr_total_amount = number_format(str_replace(',','',number_format($fr_total_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $fr_total_amount = number_format(str_replace(',','',number_format($fr_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $fr_total_amount = number_format(str_replace(',','',number_format($fr_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

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

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_y = $fr_in_tat_y + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_y = $fr_out_tat_y + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                // $report_items=DB::table('report_items')
                                //                 ->where(['report_id'=>$item->report_id])
                                //                 ->get();

                                // if(count($report_items)>0)
                                // {
                                //     $in_check = 0;

                                //     $out_check = 0;

                                //     foreach($report_items as $r_item)
                                //     {
                                //         $job_sla_items=DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id])->first();

                                //         if($job_sla_items!=NULL)
                                //         {
                                //             $date_arr=[];

                                //             $tat=$job_sla_items->tat - 1;
                                //             $incentive_tat = $job_sla_items->incentive_tat - 1;

                                //             // check if its a additional check
                                //             if($r_item->is_supplementary==1)
                                //             {
                                //                 $job_sla_i = DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id,'number_of_verifications'=>$r_item->service_item_number,'is_supplementary'=>'1'])->first();

                                //                 if($job_sla_i!=NULL)
                                //                 {
                                //                     $tat = $job_sla_i->tat - 1;
                                //                     $incentive_tat = $job_sla_i->incentive_tat - 1;
                                //                 }
                                //             }

                                //             if(stripos($item->days_type,'working')!==false)
                                //             {
                                //                 $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //             }
                                //             else if(stripos($item->days_type,'calender')!==false)
                                //             {
                                //                 $holiday_master=DB::table('customer_holiday_masters')
                                //                                 ->distinct('date')
                                //                                 ->select('date')
                                //                                 ->where(['business_id'=>$item->parent_id,'status'=>1])
                                //                                 ->orderBy('date','asc')
                                //                                 ->get();
                                //                 if(count($holiday_master)>0)
                                //                 {
                                //                     $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                //                 }
                                //                 else
                                //                 {
                                //                     $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //                 }
                                //             }

                                //             //check if task completed date is less than or equal to incentive Date
                                //             if(strtotime($date_arr['inc_tat_date']) <= strtotime($completed_date))
                                //             {
                                //                 $in_check = $in_check + 1;

                                //             }
                                //             else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                //             {
                                //                 $out_check = $out_check + 1;
                                //             }
                                //         }
                                //     }

                                //     if($in_check > $out_check)
                                //     {
                                //         $fr_in_tat_y = $fr_in_tat_y + 1;
                                //     }
                                //     else if($out_check > $in_check)
                                //     {
                                //         $fr_out_tat_y = $fr_out_tat_y + 1;
                                //     }

                                
                                    
                                // }

                                $date_arr=[];

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                // $incentive_tat=DB::table('job_sla_items')
                                //             ->select('incentive_tat')
                                //             ->where(['candidate_id'=>$item->candidate_id])
                                //             ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
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
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_y = $fr_in_tat_y + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_y = $fr_out_tat_y + 1;
                                }


                            }
                        }
                    }

                    // FR Delivered in the Month

                    $from_date = date('Y-m-01',strtotime($previous_date));

                    $to_date = date('Y-m-d',strtotime($previous_date));

                    $fr_month = DB::table('reports as r')
                                    ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                                    ->join('job_items as j','j.candidate_id','=','u.id')
                                    ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>'0','r.is_report_complete'=>1])
                                    ->whereIn('r.status',['interim','completed'])
                                    ->whereDate('r.report_complete_created_at','>=',$from_date)
                                    ->whereDate('r.report_complete_created_at','<=',$to_date)
                                    ->count();

                    // FR Delivered in the Month In & Out TAT

                    $case_detail = DB::table('reports as r')
                    ->select('u.parent_id','u.created_at as candidate_creation_date','r.report_complete_created_at','j.tat_type','j.tat','j.client_tat','j.days_type','r.id as report_id','u.id as candidate_id','j.price_type','j.package_price')
                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->where(['r.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>'0','r.is_report_complete'=>1])
                    ->whereIn('r.status',['interim','completed'])
                    ->whereDate('r.report_complete_created_at','>=',$from_date)
                    ->whereDate('r.report_complete_created_at','<=',$to_date)
                    ->get();   
                            
                    $fr_in_tat_m = 0;

                    $fr_out_tat_m = 0;

                    $fr_m_total_amount = 0.00;

                    if(count($case_detail) > 0)
                    {
                        foreach($case_detail as $item)
                        {
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($item->report_complete_created_at));

                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $fr_m_total_amount = number_format(str_replace(',','',number_format($fr_m_total_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $fr_m_total_amount = number_format(str_replace(',','',number_format($fr_m_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $fr_m_total_amount = number_format(str_replace(',','',number_format($fr_m_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

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

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_m = $fr_in_tat_m + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_m = $fr_out_tat_m + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                // $report_items=DB::table('report_items')
                                //                 ->where(['report_id'=>$item->report_id])
                                //                 ->get();

                                // if(count($report_items)>0)
                                // {
                                //     $in_check = 0;

                                //     $out_check = 0;

                                //     foreach($report_items as $r_item)
                                //     {
                                //         $job_sla_items=DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id])->first();

                                //         if($job_sla_items!=NULL)
                                //         {
                                //             $date_arr=[];

                                //             $tat=$job_sla_items->tat - 1;
                                //             $incentive_tat = $job_sla_items->incentive_tat - 1;

                                //             // check if its a additional check
                                //             if($r_item->is_supplementary==1)
                                //             {
                                //                 $job_sla_i = DB::table('job_sla_items')->where(['candidate_id'=>$r_item->candidate_id,'service_id'=>$r_item->service_id,'number_of_verifications'=>$r_item->service_item_number,'is_supplementary'=>'1'])->first();

                                //                 if($job_sla_i!=NULL)
                                //                 {
                                //                     $tat = $job_sla_i->tat - 1;
                                //                     $incentive_tat = $job_sla_i->incentive_tat - 1;
                                //                 }
                                //             }

                                //             if(stripos($item->days_type,'working')!==false)
                                //             {
                                //                 $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //             }
                                //             else if(stripos($item->days_type,'calender')!==false)
                                //             {
                                //                 $holiday_master=DB::table('customer_holiday_masters')
                                //                                 ->distinct('date')
                                //                                 ->select('date')
                                //                                 ->where(['business_id'=>$item->parent_id,'status'=>1])
                                //                                 ->orderBy('date','asc')
                                //                                 ->get();
                                //                 if(count($holiday_master)>0)
                                //                 {
                                //                     $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                //                 }
                                //                 else
                                //                 {
                                //                     $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                //                 }
                                //             }

                                //             //check if task completed date is less than or equal to incentive Date
                                //             if(strtotime($date_arr['inc_tat_date']) <= strtotime($completed_date))
                                //             {
                                //                 $in_check = $in_check + 1;

                                //             }
                                //             else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                //             {
                                //                 $out_check = $out_check + 1;
                                //             }
                                //         }
                                //     }

                                //     if($in_check > $out_check)
                                //     {
                                //         $fr_in_tat_m = $fr_in_tat_m + 1;
                                //     }
                                //     else if($out_check > $in_check)
                                //     {
                                //         $fr_out_tat_m = $fr_out_tat_m + 1;
                                //     }

                                
                                    
                                // }

                                $date_arr=[];

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                // $incentive_tat=DB::table('job_sla_items')
                                //             ->select('incentive_tat')
                                //             ->where(['candidate_id'=>$item->candidate_id])
                                //             ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
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
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $fr_in_tat_m = $fr_in_tat_m + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $fr_out_tat_m = $fr_out_tat_m + 1;
                                }


                            }
                        }
                    }

                    // Total Pending Case & Pending Cases In & Out

                    $wip_in = 0;

                    $wip_out = 0;

                    $wip_count = 0;

                    $wip_total_amount = 0.00;

                    $wip_1 = DB::table('candidate_reinitiates as u')
                                ->select('u.parent_id','u.id as candidate_id','j.price_type','j.package_price','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->where(['parent_id'=>$business_id,'user_type'=>'candidate','is_deleted'=>0])
                                ->where('j.jaf_status','<>','filled')
                                ->get();

                    if(count($wip_1)>0)
                    {
                        foreach($wip_1 as $item)
                        {
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($previous_date));

                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

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

                                if(strtotime($date_arr['inc_tat_date']) <= strtotime($completed_date))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {

                                $date_arr=[];

                                $tat = 1;

                                $incentive_tat=1;

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                $incentive_tat=DB::table('job_sla_items')
                                            ->select('incentive_tat')
                                            ->where(['candidate_id'=>$item->candidate_id])
                                            ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
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
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if(strtotime($date_arr['inc_tat_date']) <= strtotime($completed_date))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }


                            }
                        }
                    }
                    
                    $wip_2 = DB::table('reports as r')
                                ->select('u.parent_id','u.id as candidate_id','j.price_type','j.package_price','u.created_at as candidate_creation_date','j.tat_type','j.tat','j.client_tat','j.days_type')
                                ->join('candidate_reinitiates as u','r.candidate_id','=','u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled','r.status'=>'incomplete'])
                                ->get();

                    if(count($wip_2)>0)
                    {
                        foreach($wip_2 as $item)
                        {
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));

                            $completed_date = date('Y-m-d',strtotime($previous_date));

                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $wip_total_amount = number_format(str_replace(',','',number_format($wip_total_amount + $job_sla_item_price,2)),2,".","");
                            }

                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr = [];

                                $tat = $item->client_tat - 1;

                                $incentive_tat = $item->client_tat - 1;

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

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }

                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {

                                $date_arr=[];

                                $tat=DB::table('job_sla_items')
                                                ->select('tat')
                                                ->where(['candidate_id'=>$item->candidate_id])
                                                ->max('tat');

                                // $incentive_tat=DB::table('job_sla_items')
                                //             ->select('incentive_tat')
                                //             ->where(['candidate_id'=>$item->candidate_id])
                                //             ->max('incentive_tat');
                                
                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$tat);
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
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$tat);
                                    }
                                }

                                if( strtotime($completed_date) <=  strtotime($date_arr['inc_tat_date']))
                                {
                                    $wip_in = $wip_in + 1;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $wip_out = $wip_out + 1;
                                }


                            }
                        }
                    }

                    $wip_count = count($wip_1) + count($wip_2);

                    // Insuff Added Yesterday

                    $insuff_total_amount_y = 0.00;

                    $insuff = DB::table('candidate_reinitiates as u')
                                ->select('u.id as candidate_id','j.price_type','j.package_price')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                                ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                                ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'jf.is_insufficiency'=>1])
                                ->whereIn('v.status',['raised','failed'])
                                ->whereDate('v.created_at',$previous_date)
                                ->get();

                    if(count($insuff)>0)
                    {
                        foreach($insuff as $item)
                        {
                            $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();

                            if(stripos($item->price_type,'package')!==false)
                            {
                                $insuff_total_amount_y = number_format(str_replace(',','',number_format($insuff_total_amount_y + $item->package_price,2)),2,".","");

                                if(count($job_sla_item_sup)>0)
                                {
                                    foreach($job_sla_item_sup as $item_sup)
                                    {
                                        $insuff_total_amount_y = number_format(str_replace(',','',number_format($insuff_total_amount_y + $item_sup->price,2)),2,".","");
                                    }
                                }
                            }
                            else if(stripos($item->price_type,'check')!==false)
                            {
                                $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');

                                $insuff_total_amount_y = number_format(str_replace(',','',number_format($insuff_total_amount_y + $job_sla_item_price,2)),2,".","");
                            }
                        }
                    }

                    $insuff = count($insuff);

                     // Total Insuff

                     $insuff_total_amount = 0.00;

                        $total_insuff = DB::table('candidate_reinitiates as u')
                                        ->select('u.id as candidate_id','j.price_type','j.package_price')
                                        ->join('job_items as j','j.candidate_id','=','u.id')
                                        ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')
                                        ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                                        ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'jf.is_insufficiency'=>1])
                                        ->whereIn('v.status',['raised','failed'])
                                        ->get();

                        if(count($total_insuff)>0)
                        {
                            foreach($total_insuff as $item)
                            {
                                $job_sla_item_sup = DB::table('job_sla_items')->where(['candidate_id'=>$item->candidate_id,'is_supplementary'=>'1'])->get();
    
                                if(stripos($item->price_type,'package')!==false)
                                {
                                    $insuff_total_amount = number_format(str_replace(',','',number_format($insuff_total_amount + $item->package_price,2)),2,".","");
    
                                    if(count($job_sla_item_sup)>0)
                                    {
                                        foreach($job_sla_item_sup as $item_sup)
                                        {
                                            $insuff_total_amount = number_format(str_replace(',','',number_format($insuff_total_amount + $item_sup->price,2)),2,".","");
                                        }
                                    }
                                }
                                else if(stripos($item->price_type,'check')!==false)
                                {
                                    $job_sla_item_price = DB::table('job_sla_items')->where('candidate_id',$item->candidate_id)->sum('price');
    
                                    $insuff_total_amount = number_format(str_replace(',','',number_format($insuff_total_amount + $job_sla_item_price,2)),2,".","");
                                }
                            }
                        }

                        $total_insuff = count($total_insuff);

                    $name = $cust->name;

                    $email = $cust->email;
    
                    Excel::store(new DailyDataExport($today_date,$business_id),'/uploads/daily-data-export/'.$file_name,'real_public');
    
                    $url = url('/').'/uploads/daily-data-export/'.$file_name;

                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
    
                    $data=[
                            'name' =>$name,
                            'email' => $email,
                            'url'=>$url,
                            'sender'=>$sender,
                            'receiving_case'=>$receiving_case,
                            'receive_total_amount_y'=>$receive_amount,
                            'fr_total_amount_y'=>$fr_total_amount,
                            'report_delivered'=>$report_delivered,
                            'fr_in_tat_y'=>$fr_in_tat_y,
                            'fr_out_tat_y'=>$fr_out_tat_y,
                            'fr_month'=>$fr_month,
                            'fr_total_amount_m' => $fr_m_total_amount,
                            'fr_in_tat_m'=>$fr_in_tat_m,
                            'fr_out_tat_m'=>$fr_out_tat_m,
                            'wip_count' => $wip_count,
                            'wip_total_amount' => $wip_total_amount,
                            'wip_in' => $wip_in,
                            'wip_out' => $wip_out,
                            'insuff' => $insuff,
                            'insuff_total_amount_y' => $insuff_total_amount_y,
                            'total_insuff' => $total_insuff,
                            'insuff_total_amount' => $insuff_total_amount
                        ];
    
                    // Mail::send(['html'=>'mails.demo-email'], $data, function($message) use($email,$name) {
                    //     $message->to($email, $name)->subject
                    //         ('myBCD System - Daily Report Notification');
                    //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    // });

                    // Mail::send(['html'=>'mails.daily-export-report'], $data, function($message) use($email,$name) {
                    //     $message->to($email, $name)->subject
                    //         ('myBCD System - Daily Report Notification');
                    //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    // });

                    dispatch(new \App\Jobs\Admin\DailyExportMailJob($data,$email,$name));

                    // if($business_id==94)
                    // {
                    //     $mail_arr = [
                    //         ['name'=>'Jasjit','email'=>'jasjit@tagworld.in'],
                    //         ['name'=>'Pawan','email'=>'pawan@tagworld.in'],
                    //         ['name'=>'John Chenetra','email'=>'john.chenetra@premier-consultancy.com'],
                    //         ['name'=>'The Executive','email'=>'the-executive@premier-consultancy.com'],
                    //         ['name'=>'Akshay Kumar','email'=>'akshay.kumar@premier-consultancy.com'],
                    //        // ['name'=>'Neha','email'=>'neha.nivati@premier-consultancy.com'],
                    //        // ['name'=>'Mithilesh Sah','email'=>'mithilesh.techsaga@gmail.com']
                    //     ];

                    //     // $mail_arr = [
                    //     //     ['name'=>'Mithilesh Sah','email'=>'mithilesh.techsaga@gmail.com'],
                    //     // ];
                        
                    //     foreach($mail_arr as $item)
                    //     {
                    //         $name = $item['name'];
                    //         $email = $item['email'];
    
                    //         $url = url('/').'/uploads/daily-data-export/'.$file_name;
    
                    //         $sender = DB::table('users')->where(['id'=>$business_id])->first();
            
                    //         $data=[
                    //                 'name' =>$name,
                    //                 'email' => $email,
                    //                 'url'=>$url,
                    //                 'sender'=>$sender,
                    //                 'receiving_case'=>$receiving_case,
                    //                 'report_delivered'=>$report_delivered,
                    //                  'receive_total_amount_y'=>$receive_amount,
                    //                  'fr_total_amount_y'=>$fr_total_amount,
                    //                 'fr_in_tat_y'=>$fr_in_tat_y,
                    //                 'fr_out_tat_y'=>$fr_out_tat_y,
                    //                 'fr_month'=>$fr_month,
                    //                  'fr_total_amount_m' => $fr_m_total_amount,
                    //                 'fr_in_tat_m'=>$fr_in_tat_m,
                    //                 'fr_out_tat_m'=>$fr_out_tat_m,
                    //                 'wip_count' => $wip_count,
                    //                  'wip_total_amount' => $wip_total_amount,
                    //                 'wip_in' => $wip_in,
                    //                 'wip_out' => $wip_out,
                    //                 'insuff' => $insuff,
                    //                  'insuff_total_amount_y' => $insuff_total_amount_y,
                    //                 'total_insuff' => $total_insuff,
                    //                  'insuff_total_amount' => $insuff_total_amount
                    //             ];
    
                    //         Mail::send(['html'=>'mails.daily-export-report'], $data, function($message) use($email,$name) {
                    //             $message->to($email, $name)->subject
                    //                 ('myBCD System - Daily Report Notification');
                    //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    //         });
                    //     }
                    // }
                }
            }

            echo 'done';
        }
        
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
}
