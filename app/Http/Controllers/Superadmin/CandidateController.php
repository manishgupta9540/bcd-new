<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class CandidateController extends Controller
{
    
    //show the data
    public function index()
    {
        
        $items = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone')
        ->where(['u.user_type'=>'candidate'])
        ->get();
      
        return view('superadmin.candidates.index',compact('items'));
    }

    //show the detail
    public function show($id)
    {
        $customer_id = base64_decode($id);

        $item = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone')
        ->where(['u.id'=>$customer_id])
        ->first();


        return view('superadmin.candidates.show',compact('item'));
    }
}
