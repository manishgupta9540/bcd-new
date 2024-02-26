<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Help;
use App\Models\Admin\HelpAndSupportResponse;
use Illuminate\Support\Facades\Redirect;

class HelpController extends Controller
{
     // Get Feedback page
     public function help()
     {
        $parent_id =Auth::user()->parent_id;
        $business_id = Auth::user()->business_id;
        $helps = Help::where('parent_id',$business_id)->get();
         
         // CocFeedback::where('parent_id',$business_id)->get()->paginate(10);
         //   $feedbacks = $feedback->paginate(10);
         // dd($feedback);
         return view('admin.accounts.support.index',compact('helps'));
     }


      // Get Feedback page
      public function requestResolve(Request $request)
      { 
         $parent_id =Auth::user()->parent_id;
         $business_id = Auth::user()->business_id;
         $request_id =  base64_decode($request->id);
         $helps = HelpAndSupportResponse::where(['parent_id'=>$business_id,'help_request_id'=>$request_id])->first();
         $help = Help::where(['parent_id'=>$business_id,'id'=>$request_id])->first();
        //  DB::table('helps as h')
        //  ->join('help_and_support_responses as hs','hs.help_request_id','=','h.id')
        //  ->where(['h.parent_id'=>$business_id,'h.id'=>$request_id])->first();
          // CocFeedback::where('parent_id',$business_id)->get()->paginate(10);
          //   $feedbacks = $feedback->paginate(10);
          // dd($feedback);
          return view('admin.accounts.support.help',compact('helps','help'));
      }



    //Store Feedback data
    public function store(Request $request)
    {
        $help_id =  base64_decode($request->id);
        // dd($help_id);
        
              
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $parent_id =Auth::user()->parent_id;
        // dd($request->help);

        $this->validate($request, [
           
            'help' => 'required',
            
            ]);
        DB::beginTransaction();
        try{
            //Check Data is exist in database or not
            $help = Help::where(['parent_id'=>$business_id,'id'=>$help_id])->first();
            // dd($help);
            if ($help) {
                
                $help->resolve_at = date('Y-m-d H:i:s');
                $help->resolve_by = $user_id;
                $help->updated_at = date('Y-m-d H:i:s');
                $help->save();

                $helps = HelpAndSupportResponse::where(['parent_id'=>$business_id,'help_request_id'=>$help_id])->first();
                if ($helps) {
                
                DB::table('help_and_support_responses')->where(['parent_id'=>$business_id,'help_request_id'=>$help_id])->update(['response_content'=>$request->help,'response_at'=>date('Y-m-d H:i:s'),'response_by'=>$user_id]); 
                    
                }
                else {
                    $help_response =new HelpAndSupportResponse;
                    $help_response->parent_id = $help->parent_id;
                    $help_response->business_id = $help->business_id;
                    $help_response->help_request_id =$help_id;
                    $help_response->response_content= $request->help;
                    $help_response->response_at = date('Y-m-d H:i:s');
                    $help_response->response_by = $user_id;
                    $help_response->save();
                }

                DB::commit();
                return redirect('/help')
                ->with('success', 'Response  Updated successfully');

            }
            return Redirect::back()->withErrors(['success', 'Response not Updated ']);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
        
    }
}
