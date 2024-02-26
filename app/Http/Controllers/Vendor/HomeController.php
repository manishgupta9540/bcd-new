<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Vendor\VendorTask;
use App\Traits\CommonTrait;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business_id=Auth::user()->business_id;
        return view('vendor.home');
    }

    public function dashboardTatCard(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        // $tat = DB::table('vendor_sla_items as sla')
        //         ->select('sla.tat','service_id')   
        //         ->get();      
       
        // $data = DB::table('vendor_tasks as vt')
        //             ->select('vt.assigned_at','vt.reassigned_at','service_id')            
        //             ->get();

        $total_in = 0;

        $total_out = 0;

        $tasks =  VendorTask::from('vendor_tasks as vt')
                    ->select('t.name','vt.*','s.name as servicename')
                    ->join('tasks as t','t.id','=','vt.task_id')
                    ->join('services as s','s.id','=','vt.service_id')
                    ->where(['vt.business_id'=>Auth::user()->id])
                    ->whereIn('vt.status',['1','2'])->orderBy('vt.id','DESC')
                    ->get();

        if(count($tasks)>0)
        {
            foreach($tasks as $task)
            {
                if($task->status=='1')
                {
                    $start_date = date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = date('Y-m-d');

                    $date_arr = [];

                    $tat = 1;

                    $incentive_tat = 1;

                    $tat = $task->tat;

                    $incentive_tat = $task->tat;

                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);

                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_out+=1;
                    }
                    else
                    {
                        $total_in+=1;
                    }
                }
                else if($task->status=='2')
                {
                    $start_date = date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = date('Y-m-d',strtotime($task->completed_at));

                    $date_arr = [];

                    $tat = 1;

                    $incentive_tat = 1;

                    $tat = $task->tat;

                    $incentive_tat = $task->tat;

                    $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);

                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $total_out+=1;
                    }
                    else
                    {
                        $total_in+=1;
                    }
                }
            }
        }
       
        $viewRender = view('vendor.dashboard.tat-card',compact('tasks','total_in','total_out'))->render();
	    return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    public function dashboardTaskCard(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $task_count = DB::table('vendor_tasks as vt')
                ->select('t.name','vt.*')
                ->join('tasks as t','t.id','=','vt.task_id')
                ->where(['vt.business_id'=>Auth::user()->id])
                ->count();

        $completed_task = DB::table('vendor_tasks as vt')
                ->select('t.name','vt.*')
                ->join('tasks as t','t.id','=','vt.task_id')
                ->where(['vt.business_id'=>Auth::user()->id])
                ->where(['vt.status'=>'2'])
                ->count(); 
        $pending_task = DB::table('vendor_tasks as vt')
                ->select('t.name','vt.*')
                ->join('tasks as t','t.id','=','vt.task_id')
                ->where(['vt.business_id'=>Auth::user()->id])
                ->where(['vt.status'=>'1'])
                ->count();         
                     

        $viewRender = view('vendor.dashboard.task-card',compact('task_count','completed_task','pending_task'))->render();
        return response()->json(array('success'=>true, 'html'=>$viewRender));
    }

    public function dashboardInsuffCard(Request $request)
    {
        $user_id = Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $user_type = Auth::user()->user_type;

        $insuff_count = DB::table('vendor_insufficiencies')->count();
                
        $raise_insuff_count = DB::table('vendor_insufficiencies')
                            ->where('status','raise')
                            ->count(); 
                                  
        $clear_insuff_count = DB::table('vendor_insufficiencies')
                            ->where('status','cleared')
                            ->count();

        $viewRender = view('vendor.dashboard.insuff-card',compact('insuff_count','raise_insuff_count','clear_insuff_count'))->render();
        return response()->json(array('success'=>true, 'html'=>$viewRender));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
