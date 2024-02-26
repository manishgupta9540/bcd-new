<?php

namespace App\Http\Controllers\Client;

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
        return view('clients.accounts.feedback');
    }

    //Store Feedback data
    public function store(Request $request)
    {
        $this->validate($request, [
           
            'feedback' => 'required',
            ]);
              
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $parent_id =Auth::user()->parent_id;

        $new_feedback = new CocFeedback();
        $new_feedback->business_id = $business_id;
        $new_feedback->parent_id = $parent_id;
        $new_feedback->feedback = $request->feedback;
        $new_feedback->created_by = $user_id;
        $new_feedback->save();
        
        return redirect('/my/feedback')
            ->with('success', 'Feedback has been sent successfully');
    }
}
