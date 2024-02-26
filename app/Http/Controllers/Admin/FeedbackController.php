<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CocFeedback;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    // Get Feedback page
    public function feedback()
    {

        $business_id= Auth::user()->business_id;        
        $feedback = DB::table('coc_feedback')->where('parent_id',$business_id)->get();
        
        // CocFeedback::where('parent_id',$business_id)->get()->paginate(10);
        //   $feedbacks = $feedback->paginate(10);
        // dd($feedback);
        return view('admin.accounts.feedback',compact('feedback'));
    }
}
