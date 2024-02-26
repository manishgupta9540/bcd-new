<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\GuestHelp;
use App\Models\GuestHelpAndSupportResponse;
use Illuminate\Support\Facades\Redirect;
class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $items=DB::table('users')
                ->where(['parent_id'=>$business_id,'user_type'=>'guest','is_deleted'=>0])
                // ->orderBy('id','desc')
                ->orderBy('updated_at','desc');
                if($request->get('from_date') !=""){
                    $items->whereDate('created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $items->whereDate('created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('name') !=""){
                    $items->where('name',$request->get('name'));
                }
                if($request->get('email') !=""){
                    $items->where('email',$request->get('email'));
                }
                if($request->get('phone') !=""){
                    $items->where('phone',$request->get('phone'));
                }
        $items=$items->paginate(10);
        if($request->ajax())
            return view('superadmin.guest.ajax',compact('items'));
        else
            return view('superadmin.guest.index',compact('items'));
    }

    public function orders(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $items=DB::table('guest_instant_masters as g')
                        ->select('g.*',DB::raw('group_concat(gc.service_id) as services'),DB::raw('group_concat(gc.id) as g_c_id'))
                        ->join('guest_instant_carts as gc','g.id','=','gc.giv_m_id')
                        ->where(['g.parent_id'=> $business_id])
                        ->whereIn('g.status',['success','failed'])
                        ->orderBy('g.id','desc')
                        ->groupBy('gc.giv_m_id');

                if(is_numeric($request->get('service_id'))){
                    $items->where('gc.service_id',$request->get('service_id'));
                }
                if(is_numeric($request->get('user_id'))){
                    $items->where('g.business_id',$request->get('user_id'));
                }
                if($request->get('from_date') !=""){
                    $items->whereDate('g.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                $items->whereDate('g.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('order_id')){
                    $items->where('g.order_id','like',$request->get('order_id').'%');
                }
        $items=$items->paginate(10);

        $services=DB::table('services')->where(['verification_type'=>'Auto','status'=>1])->whereNotIn('name',['GSTIN','Telecom','Covid-19 Certificate'])->get();

        $guests=DB::table('users')
                    ->where(['parent_id'=>$business_id,'user_type'=>'guest','is_deleted'=>0])
                    ->get();

        if($request->ajax())
            return view('superadmin.guest.order_ajax',compact('items','services','guests'));
        else
            return view('superadmin.guest.order',compact('items','services','guests'));
    }

    public function orderDetails(Request $request,$id)
    {
        $guest_cart_id=Crypt::decryptString($id);

        // dd($guest_cart_id);

        // dd(1);

        $query=DB::table('guest_instant_cart_services as gcs')
                            ->select('gcs.*','s.name')
                            ->join('services as s','s.id','=','gcs.service_id')
                            ->where(['giv_c_id'=>$guest_cart_id]);
                            if($request->get('from_date') !=""){
                                $query->whereDate('gcs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            }
                            if($request->get('to_date') !=""){
                                $query->whereDate('gcs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            }
        $items = $query->orderBy('gcs.service_id','asc')->paginate(10);
        

        if ($request->ajax())
            return view('superadmin.guest.order_details_ajax', compact('items'));
        else
            return view('superadmin.guest.order_details', compact('items'));   

    }

    public function orderDetailsData(Request $request)
    {
        $d_id=base64_decode($request->g_id);

        // dd($d_id);

        $form='';
        $modal='';
        $guest_cart_service=DB::table('guest_instant_cart_services as gcs')
                                ->select('gcs.*','s.name')
                                ->join('services as s','s.id','=','gcs.service_id')
                                ->where(['gcs.id'=>$d_id])
                                ->first();

        if($guest_cart_service->service_data!=NULL)
        {
            
            $modal.='<div class="modal-header">
                        <h4 class="modal-title" id="service_name"></h4>
                    </div>
                    <!-- Modal body -->
                    <input type="hidden" name="g_id" id="g_id">
                        <div class="modal-body">
                            <div id="order_details">

                            </div>
                        </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                    
                    </div>';
            
            
            
            $service_data_array=json_decode($guest_cart_service->service_data,true); 

            $form.='<div class="row">';
                
            foreach ($service_data_array as $service_key => $service_value)
            {
                $i=0;
                $form.='<div class="col-md-12">';
                // if(stripos($service_key,'candidate')!==false)
                // {
                //     $form.='<h3>Candidate Info</h3>';
                // }
                // else
                // {
                    if(stripos($service_key,'check')!==false)
                    {
                        $form.='<h3>Checks Info</h3>';
                        $form.='<p class="pb-border"></p></div>';
                    }
                    
                // }
               

                foreach ($service_value as $key => $value)
                {
                    if(stripos($service_key,'check')!==false)
                    {
                        $form.='<div class="col-md-12">
                                <div class="form-group">
                                    <label>'.$key.' : '.$value.'</label>
                                </div>
                            </div>';
                    }
                   
                    
                }
            }
            
            $form.='</div>';
            
        }
        
        return response()->json([
            'data' => $guest_cart_service,
            'form' => $form,
            'modal' => $modal
        ]);
    }

    // Get Feedback page
    public function help(Request $request)
    {
       $parent_id =Auth::user()->parent_id;
       $business_id = Auth::user()->business_id;
       $helps = DB::table('guest_helps as gs')
                ->select('gs.*')
                ->join('users as u','u.id','=','gs.business_id')
                ->where(['gs.parent_id'=>$business_id,'u.is_deleted'=>0,'u.user_type'=>'guest']);
                if($request->get('from_date') !=""){
                    $helps->whereDate('gs.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $helps->whereDate('gs.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if(is_numeric($request->get('user_id'))){
                    $helps->where('gs.business_id',$request->get('user_id'));
                }

        $helps=$helps->paginate(10);

        $guests=DB::table('users')
                ->where(['parent_id'=>$business_id,'user_type'=>'guest','is_deleted'=>0])
                ->get();
        
        if($request->ajax())
            return view('superadmin.guest.help.ajax',compact('helps','guests'));
        else
            return view('superadmin.guest.help.index',compact('helps','guests'));
    }

    // Get Feedback page
    public function requestResolve(Request $request)
    { 
       $parent_id =Auth::user()->parent_id;
       $business_id = Auth::user()->business_id;
       $request_id =  base64_decode($request->id);
       $helps = GuestHelpAndSupportResponse::where(['parent_id'=>$business_id,'help_request_id'=>$request_id])->first();
       $help = GuestHelp::where(['parent_id'=>$business_id,'id'=>$request_id])->first();
        
        return view('superadmin.guest.help.response',compact('helps','help'));
    }

    public function resolveStore(Request $request)
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
            $help = GuestHelp::where(['parent_id'=>$business_id,'id'=>$help_id])->first();
            // dd($help);
            if ($help) {
                
                $help->resolve_at = date('Y-m-d H:i:s');
                $help->resolve_by = $user_id;
                $help->updated_at = date('Y-m-d H:i:s');
                $help->save();

                $helps = GuestHelpAndSupportResponse::where(['parent_id'=>$business_id,'help_request_id'=>$help_id])->first();
                if ($helps) {
                
                DB::table('guest_help_and_support_responses')->where(['parent_id'=>$business_id,'help_request_id'=>$help_id])->update(['response_content'=>$request->help,'response_at'=>date('Y-m-d H:i:s'),'response_by'=>$user_id]); 
                    
                }
                else {
                    $help_response =new GuestHelpAndSupportResponse;
                    $help_response->parent_id = $help->parent_id;
                    $help_response->business_id = $help->business_id;
                    $help_response->help_request_id =$help_id;
                    $help_response->response_content= $request->help;
                    $help_response->response_at = date('Y-m-d H:i:s');
                    $help_response->response_by = $user_id;
                    $help_response->save();
                }

                DB::commit();
                return redirect('/app/guest/help')
                ->with('success', 'Response Updated successfully');

            }
            return Redirect::back()->withErrors(['success', 'Response not Updated ']);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    /**
     * Guest Check Price
     *
     * @return \Illuminate\Http\Response
     */
    public function checkPrice(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $items=DB::table('guest_instant_check_prices as g')
                ->select('g.*','s.name','s.verification_type')
                ->join('services as s','s.id','=','g.service_id')
                ->where(['g.business_id'=>$business_id]);
                // if($request->get('from_date') !=""){
                //     $items->whereDate('g.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                // }
                // if($request->get('to_date') !=""){
                //     $items->whereDate('g.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                // }
                if(is_numeric($request->get('service_id'))){
                    $items->where('g.service_id',$request->get('service_id'));
                }
        $items=$items->paginate(10);

        $services =  DB::table('services as s')
                        ->where(['s.verification_type'=>'Auto','s.status'=>1,'s.business_id'=>NULL])
                        ->whereNotIn('s.name',['GSTIN','Telecom','Covid-19 Certificate'])
                        ->get();

        if($request->ajax())
            return view('superadmin.guest.checkprice.ajax',compact('items','services'));
        else
            return view('superadmin.guest.checkprice.index',compact('items','services'));
    }

    public function checkPriceEdit(Request $request)
    {
        $id=base64_decode($request->id);

        if ($request->isMethod('get'))
        {
            $data = DB::table('guest_instant_check_prices as g')
                        ->select('g.price','s.name')
                        ->join('services as s','s.id','=','g.service_id')
                        ->where(['g.id' =>$id])        
                        ->first(); 
        
            return response()->json([                
                'result' => $data
            ]);
        }

        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;

        $rules= [
            'price'    => 'required|numeric|min:1',
         ];
        
         $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         DB::table('guest_instant_check_prices')->where(['id'=>$id])->update([
             'price' => $request->price,
             'updated_by' =>$user_id,
             'updated_at' => date('Y-m-d H:i:s'),
         ]);

         return response()->json([
            'fail' => false,
         ]);
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
    public function destroy(Request $request)
    {
        //
        $id=base64_decode($request->id);
        $user = User::find($id);
        Session::getHandler()->destroy($user->session_id);
        DB::table('users')->where('id',$id)->update([
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            'status' => 'ok'
        ]);

    }

    public function guestStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);

        if($type=='active')
        {
            DB::table('users')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('users')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    public function resendMail(Request $request)
    {
        $guest_id =  base64_decode($request->id);

        try{

            $user = DB::table('users')->where(['id'=>$guest_id,'is_deleted'=>'0'])->first();
            if($user!=NULL)
            {
                if($user->is_email_verified==1)
                {
                    return response()->json([
                        'status' =>'ok',
                        'name' => $user->name,
                        'mail_verify' => 1,
                        'message' => 'Already done the Mail Verification !!'
                    ]);
                }

                $token=Str::random(50);

                DB::table('users')->where(['id'=>$guest_id])->update([
                    'email_verification_token' => $token,
                    'email_verification_resend_at' =>  date('Y-m-d H:i:s'),
                ]);

                $name=$user->name;

                $email=$user->email;

                $data=['name' =>$name,'email' => $email,'token' => $token];

                Mail::send(['html'=>'mails.email-verify'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Email Verification');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });

                return response()->json([
                    'status' =>'ok',
                    'name' => $user->name,
                    'mail_verify' => 0,
                    'message' => 'Mail Send Successfully !!'
                ]);
            }

            return response()->json([
                'status' =>'no',
                'message' => 'Something Went Wrong!'
            ]);
            
        }
        catch (\Exception $e) {
            // something went wrong
            return $e;
        }  
    }
}
