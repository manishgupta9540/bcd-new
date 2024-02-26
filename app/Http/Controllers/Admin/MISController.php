<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MISController extends Controller
{
    public function mis(Request $request)
    {

        $business_id =Auth::user()->business_id;
        $activities = ActivityLog::all();
        // $cocs = DB::table('users')->where('user_type','client')->get(); 
      
        // $slas = DB::table('customer_sla')->where('parent_id',$business_id)->get();
        // $candidates = DB::table('users')->where('user_type','candidate')->get();
        // $hold_candidates= DB::table('candidate_hold_statuses')->where('parent_id',$business_id)->get();

        // $filling_tasks = DB::table('tasks as t')
        // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        // ->join('users as u', 't.candidate_id', '=', 'u.id')
        // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        // ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'description'=>'JAF Filling','t.is_completed'=>'1'])
        // ->orderBy('id','DESC')->get();
        // $raise_insuff = DB::table('users')

        // dd($activities);
        if ($request->ajax())
            return view('admin.accounts.mis.ajax',compact('activities'));
        else
            return view('admin.accounts.mis.index',compact('activities'));
    }
}
