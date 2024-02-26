<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class FaqController extends Controller
{
    //
    public function index()
    {
        $parent_id=Auth::user()->parent_id;
        // $user_id=Auth::user()->id;
        // $parent_id=Auth::user()->parent_id;
        $faq=DB::table('faq')
                ->where(['status'=>0,'business_id'=>$parent_id])
                ->get();
        return view('clients.accounts.faq.index',compact('faq'));
    }

    // public function show($id)
    // {
    //     $faq_id = base64_decode($id);
    //     // $permission = Permission::get();
    //     $faq = DB::table("faq")->where('id', $faq_id)->first();
           
    //     return view('clients.accounts.faq.show', compact('faq'));
    // }
}
