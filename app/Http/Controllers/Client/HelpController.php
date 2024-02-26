<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Help;

class HelpController extends Controller
{
    public function help()
    {
       $parent_id =Auth::user()->parent_id;
       $user_id=Auth::user()->id;
       $business_id=Auth::user()->business_id;
       $helps = Help::where(['business_id'=>$business_id,'request_generated_by'=>$user_id])->get();
        // dd($helps);
        // CocFeedback::where('parent_id',$business_id)->get()->paginate(10);
        //   $feedbacks = $feedback->paginate(10);
        // dd($feedback);
        return view('clients.accounts.support.index',compact('helps'));
    }

    public function createQuestion()
    {
        return view('clients.accounts.support.create');
    }

    public function saveQuestion(Request $request)
    {
       
        $this->validate($request, [
            'question' => 'required',
            'subject' => 'required',
            ]);
              
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $parent_id=Auth::user()->parent_id;
        $help_data=[
            'parent_id' => $parent_id,
            "business_id" => $business_id,
            "content" => $request->question,
            "subject" => $request->subject,
            "request_generated_by" => $user_id,
            "created_at" => date('Y-m-d H:i:s'),
            // "updated_at" => date('Y-m-d H:i:s')
        ];

        DB::table('helps')->insert($help_data);
        
        return redirect('/my/help')
            ->with('success', 'Question created successfully');

    }


}
