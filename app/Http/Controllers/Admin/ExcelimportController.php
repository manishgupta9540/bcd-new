<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CandidatesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class ExcelimportController extends Controller
{

    function importCandidateForm()
    {
        $business_id = Auth::user()->business_id;
        $customers = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['user_type'=>'client','parent_id'=>$business_id])
        ->get();

        return view('admin.candidates.imports.excel', compact('customers'));
    }

    function importCandidate(Request $request)
    {
     
        $data =  Excel::import(new CandidatesImport, request()->file('excelFile'));

        dd($data);
           
        // return back();
    }
}