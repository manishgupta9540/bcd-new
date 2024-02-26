<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class AppController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('memory_limit', '-1');

        ini_set('max_execution_time', '0');

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->middleware('auth');
    }


    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $company= DB::table('users')->select('company_logo')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('superadmin.settings.general', compact('company'));
    }

    public function profile()
    {
        $profile = User::find(Auth::user()->id);
        
        $business = DB::table('user_businesses')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('superadmin.settings.profile', compact('profile','business'));
    }

    public function update_profile(Request $request)
    {
        $id=Auth::user()->id;
        $this->validate($request, [
            'first_name'   => 'required|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
            'last_name'     => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'phone'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
        ]
       );

       if($request->has('company_name') && $request->company_name!='')
        {
            if($request->job_title=='')
            {
                $this->validate($request, [
                    'title' => 'required'
                ]
               );          
            }                              
        }
        $phone = preg_replace('/\D/', '', $request->input('phone'));
        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        DB::table('users')->where('id',$id)->update([
            'first_name'    =>$request->input('first_name'),
            'last_name'     =>$request->input('last_name'),
            'name'          =>$name,
            'phone'         =>$phone,
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }


    /**
     * Show the JAF.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jaf()
    {
        $company= DB::table('users')->select('company_logo')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('superadmin.settings.jaf', compact('company'));
    }

    /**
     * Show the sla.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla()
    {
        $business_id = Auth::user()->business_id;

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*','u.first_name','u.last_name','ub.company_name')
                ->join('users as u','u.id','=','sla.business_id')
                ->join('user_businesses as ub','ub.business_id','=','sla.business_id')
                ->where(['u.parent_id'=>Auth::user()->business_id])
                ->get();
        
        $customers = DB::table('users as u')
                ->select('u.id','u.first_name','u.last_name','b.company_name')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'customer'])
                ->whereNotIn('u.id',[$business_id])
                ->get();

        return view('superadmin.settings.sla', compact('sla'));
    }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla_create()
    {
        $business_id = Auth::user()->business_id;

        $customers = DB::table('users as u')
                ->select('u.id','u.first_name','u.last_name','b.company_name')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.parent_id'=>$business_id,'u.user_type'=>'customer'])
                ->whereNotIn('u.id',[$business_id])
                ->get();

        $services = DB::table('services')->select('*')
        ->get();

        return view('superadmin.settings.create-sla', compact('customers','services'));
    }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla_save(Request $request)
    {
        //dd($request);

        $this->validate($request, 
        [
            'customer'  => 'required', 
            'name'      => 'required', 
        ]);

        $business_id = Auth::user()->business_id;
        $data = [
                    'business_id'=> $request->input('customer'),
                    'parent_id'  => $business_id,
                    'title'      => $request->input('name'),
                    'created_at' => date('Y-m-d H:i:s')
                ];

        $sla_id = DB::table('customer_sla')->insertGetId($data);
        $i = 0;
        $number_of_verifications =1;
       
        if( count($request->input('services') ) > 0 ){
            foreach($request->input('services') as $service){

               $number_of_verifications = $request->input('service_unit-'.$service);
               $notes = $request->input('notes-'.$service);
                
                $data = [
                    'business_id'=>$request->input('customer'),
                    'sla_id'     =>$sla_id,
                    'number_of_verifications'=>$number_of_verifications,
                    'service_id' =>$service,
                    'notes' =>$notes,
                    'created_at' =>date('Y-m-d H:i:s')
                ];

                DB::table('customer_sla_items')->insert($data);

                $i++;
            }
        }

        return redirect('/app/settings/sla')
            ->with('success', 'SLA created successfully.');
        
    }

    /**
     * Show the edit sla.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla_edit($id)
    {

        $business_id = Auth::user()->business_id;

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*','u.company_name')
                ->join('user_businesses as u','u.business_id','=','sla.business_id')
                ->where(['sla.id'=>$id])
                ->first();
        
        $sla_items = DB::table('customer_sla_items as sla')
        ->select('s.id','s.name','sla.number_of_verifications','sla.notes','sla.id as sla_item_id')
        ->join('services as s','s.id','=','sla.service_id')
        ->where(['sla.sla_id'=>$id])
        ->get();

        $selected_services_id = [];
        foreach($sla_items as $item){
            $selected_services_id[] = $item->id;
        }
        
        $services= DB::table('services')
            ->select('*')
            ->get();

        return view('superadmin.settings.sla-edit', compact('services','sla','sla_items','selected_services_id'));
    }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla_update(Request $request)
    {
        //dd($request);

        $this->validate($request, 
        [
            'name'      => 'required', 
        ]);

        $business_id = Auth::user()->business_id;
        $data = [
                    'title'      =>$request->input('name'),
                    'updated_at' =>date('Y-m-d H:i:s')
                ];

        DB::table('customer_sla')->where(['id'=>$request->input('sla_id')])->update($data);
                
        //update service items
        DB::table('customer_sla_items')->where(['sla_id'=>$request->input('sla_id')])->delete();
        
        if( count($request->input('services') ) > 0 ){
            foreach($request->input('services') as $service){
                
                $data = [
                    'business_id'=>$request->input('business_id'),
                    'sla_id'     =>$request->input('sla_id'),
                    'number_of_verifications'=>'1',
                    'service_id' =>$service,
                    'created_at' =>date('Y-m-d H:i:s')
                ];

                DB::table('customer_sla_items')->insert($data);
            }
        }

        return redirect('/app/settings/sla')
            ->with('success', 'SLA updated successfully.');
        
    }

    

    // upload  file.
    function uploadCompanyLogo(Request $request)
    {        
            // dd($request);
            $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw","doc","pdf","docx","jpg","png","jpeg","PNG","JPG","JPEG","csv","gif","txt",".apk");

        if( $request->hasFile('file') ) {
            
            $result = array($request->file('file')->getClientOriginalExtension());

            if(in_array($result[0],$extensions))
            {                                
                $attachment_file  = $request->file('file');
                $orgi_file_name   = $attachment_file->getClientOriginalName();
                
                $fileName         = pathinfo($orgi_file_name,PATHINFO_FILENAME);

                $filename         = $fileName.'-'.time().'.'.$attachment_file->getClientOriginalExtension();
                $dir              = public_path('/uploads/company-logo/');            
                $request->file('file')->move($dir, $filename);
                    
                $asset_id = NULL;
                $is_temp = 1;

                DB::table('users')          
                ->where(['business_id'=>Auth::user()->business_id])  
                ->update([                    
                                'company_logo'  => $filename,
                                'updated_at'    => date('Y-m-d H:i:s'),
                            ]);                                

                    // file type 
                    $type = url('/').'/images/file.jpg';
                    $extArray = explode('.', $filename);
                    $ext = end($extArray);
                
                    if($filename != NULL)
                    {
                        
                        if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                        {              
                            $type = url('/').'/uploads/company-logo/'.$filename;
                        }
                        
                    }           

                    return response()->json([
                        'fail' => false,
                        'filename' => $filename,
                        'filePrev'=>$type
                ]); 

            }
            else{
                // Do something when it fails
                return response()->json([
                    'fail' => true,
                    'errors' => 'File type error!'
                ]);
            }

        }
        else{
            // Do something when it fails
            return response()->json([
                'fail' => true,
                'errors' => 'Please enter required input!'
            ]);
        }

    }

    public function checkPriceMaster(Request $request)
    {
        // $parent_id=Auth::user()->parent_id;
        // dd($parent_id);
        $business_id=Auth::user()->business_id;
        
        $checkserviceprice=DB::table('check_price_masters as cm')
                            ->select('s.name as service_name','cm.price as default_price','cm.id as check_price_id','s.verification_type','s.id as service_id')
                            ->join('services as s','s.id','=','cm.service_id')
                            // ->join('service_form_inputs as si','s.id','=','si.service_id')
                            ->where(['cm.business_id'=>$business_id,'s.status'=>'1','s.verification_type'=>'Auto']);
                            if(is_numeric($request->get('service_id'))){
                                $checkserviceprice->where('cm.service_id',$request->get('service_id'));
                            }
                            $items=$checkserviceprice
                                        // ->groupBy('si.service_id')
                                        ->paginate(10);
        // dd($checkserviceprice);
        $services=DB::table('services as s')
                    ->select('s.name','s.id','s.verification_type')
                    // ->join('service_form_inputs as si','s.id','=','si.service_id')
                    ->where('s.status','1')
                    ->where('s.business_id',NULL)
                    ->where('s.verification_type','Auto')
                    // ->groupBy('si.service_id')
                    ->get();
                    
        if($request->ajax())
            return view('superadmin.settings.checkprice.ajax',compact('items','services'));
        else
            return view('superadmin.settings.checkprice.index',compact('items','services'));
    }

    public function checkPriceUpdate(Request $request){
        
        $id=base64_decode($request->id);

        $rules= [
            'price'         => 'required|numeric'
            
         ];
        $validator = Validator::make($request->all(), $rules);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type'=>'validation',
                 'errors' => $validator->errors()
             ]);
         }

        DB::table('check_price_masters')->where(['id'=>$id])->update([
            'price' =>$request->price,
            'updated_by'=> Auth::user()->id,
            'updated_at'=>date('Y-m-d h:i:s')
        ]);
        
        return response()->json([
            'fail' => false,
        ]);

    }

    public function checkPriceStore(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;

        $rules= 
        [
            // 'customer'   => 'required',
            'services'    => 'required|array|min:1', 
            'new_price'  => 'required|numeric', 
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'error_type'=> 'validation',
                'errors' => $validator->errors()
            ]);
        }

        $checkprice=DB::table('check_price_masters')
                    ->whereIn('service_id',[$request->services])
                    ->count();
        
        // dd($checkprice);

        if($checkprice > 0)
        {
            return response()->json([
                'fail' => true,
                'error_type'=> 'validation',
                'errors' => ['service'=>'Service Price is Already Exist!!']
            ]);
        }

        if(count($request->services)>0)
        {
            foreach($request->services as $service_id)
            {
                DB::table('check_price_masters')->insert(
                    [
                        'business_id' => $business_id,
                        'service_id' => $service_id,
                        'created_by' => $user_id,
                        'used_by'  => 'superadmin',
                        'price' => $request->new_price,
                        'created_at' => date('Y-m-d h:i:s')
                    ]
                    );
            }
        }

        return response()->json([
            'fail' => false,
        ]);

    }

    /*** Show the billing history
     * ** @return \Illuminate\Http\Response*/
    
    public function billing(Request $request)
    {
            $business_id=Auth::user()->business_id;
            $billings=DB::table('billings as b')
            ->select('b.*','u.name','ub.company_name')
            ->join('users as u','u.id','=','b.business_id')
            ->join('user_businesses as ub','ub.business_id','=','u.id')
            ->where(['b.parent_id'=>$business_id]);
            if(is_numeric($request->get('customer_id'))){
                $billings->where('b.business_id',$request->get('customer_id'));
            }
            if($request->get('from_date') !=""){
                $billings->whereDate('b.start_date','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
              if($request->get('to_date') !=""){
                $billings->whereDate('b.end_date','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            $billings=$billings->paginate(10);

            $customers =DB::table('users as u')
            ->select('u.id','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            ->join('user_businesses as b','b.business_id','=','u.id')
            ->where(['u.user_type'=>'customer','u.parent_id'=>$business_id])
            ->get();

        if($request->ajax())
            return view('superadmin.settings.billing.ajax', compact('billings','customers'));
        else
            return view('superadmin.settings.billing.index', compact('billings','customers'));
    } 

    public function billing_details(Request $request,$id)
    {
            $business_id=Auth::user()->business_id;

            $billing_id=base64_decode($id);

            $billing_details=DB::table('billing_items as bi')
            ->select('bi.*','s.verification_type')
            ->join('services as s','s.id','=','bi.service_id')
            ->where(['bi.billing_id'=>$billing_id]);
            if(is_numeric($request->get('customer_id'))){
                $billing_details->where('bi.service_id',$request->get('customer_id'));
            }
            if($request->get('from_date') !=""){
                $billing_details->whereDate('bi.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
              if($request->get('to_date') !=""){
                $billing_details->whereDate('bi.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            $billing_details=$billing_details->paginate(10);

            // dd($billings);

            // $customers = DB::table('users as u')
            // ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
            // ->join('user_businesses as b','b.business_id','=','u.id')
            // ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
            // ->get();
            $services=DB::table('services as s')
                    ->select('s.name','s.id','s.verification_type')
                    ->where('status','1')
                    ->get();
            $billing=DB::table('billings as b')->where('id',$billing_id)->first();

        if($request->ajax())
            return view('superadmin.settings.billing.billing_details_ajax', compact('billing_details','billing','services'));
        else
            return view('superadmin.settings.billing.billing_details', compact('billing_details','billing','services'));
    }

    public function promocode(Request $request)
    {
        $business_id=Auth::user()->business_id;

        $promocodes=DB::table('promocodes')
                    ->where(['business_id'=>$business_id,'is_deleted'=>0,'is_expired'=>0])
                    ->get();

        if(count($promocodes)>0)
        {
            foreach($promocodes as $p)
            {
                if($p->end_date!=NULL || $p->end_date!="")
                {
                    $end_date=date('Y-m-d h:i a',strtotime($p->end_date));
                    $end_times=strtotime($end_date);

                    if(strtotime(date('Y-m-d h:i a')) >= $end_times)
                    {
                        DB::table('promocodes')->where('id',$p->id)->update(['is_expired'=>1]);
                    }
                }
            } 
        }

        $items=DB::table('promocodes')
                ->where('business_id',$business_id)
                ->where('is_deleted',0);
                if($request->get('from_date') !=""){
                    $items->whereDate('created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $items->whereDate('created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
        $items=$items->paginate(10);


        if($request->ajax())
            return view('superadmin.settings.promocode.ajax', compact('items'));
        else
            return view('superadmin.settings.promocode.index', compact('items'));
    }

    public function promoCreate(Request $request)
    {
        return view('superadmin.settings.promocode.create');
    }

    public function promoStore(Request $request)
    {
        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;
        $rules= [
            'code_name'    => 'required|regex:/^(?=[A-Z])[A-Z0-9]+$/u|min:3|max:10',
            'type'  => 'required|in:percentage,fixed_amount',
            'value' => 'required',
            'uses_limit' => 'required|integer|min:1',
            'start_date' =>  'required|date',
            'end_date' =>'required|date',
            'start_time'  => 'required|date_format:h:i a',
            'end_time'  => 'required|date_format:h:i a',
         ];

         $custom=[
             'value.numeric' => 'Value should be numeric or decimal',
             'value.integer' => "Value should must be numeric",
             'code_name.regex' => 'Code Name should be Alphanumeric',
             'uses_limit.integer' => 'Uses Limit must be numeric'
         ];
        
         $validator = Validator::make($request->all(), $rules, $custom);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }

         // check if promocode already exist
         $promocount=DB::table('promocodes')->where(DB::raw('BINARY `title`'),$request->code_name)->where(['is_deleted'=>0])->count();

         if($promocount > 0)
         {
            return response()->json([
                'success' => false,
                'errors' => ['code_name'=> 'Code Name is Already Exist']
            ]);
         }

         if($request->type=='percentage')
         {
            //  dd($request->type);
            $rules=[
                'value' => 'numeric|min:1|max:5'
            ];
            $validator = Validator::make($request->all(), $rules,$custom);
          
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
            
         }
         else if($request->type=='fixed_amount')
         {
            $rules=[
                'value' => 'integer|min:1|max:10'
            ];
            
            $validator = Validator::make($request->all(), $rules,$custom);
          
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
         }

         $start_date=date('Y-m-d',strtotime($request->start_date));

         $end_date=date('Y-m-d',strtotime($request->end_date));

         $start_times=strtotime($start_date.' '.$request->start_time);

         $end_times=strtotime($end_date.' '.$request->end_time);

         $diff = $end_times - $start_times;

         if($diff<=0)
         {
            return response()->json([
                'success' => false,
                'errors' => ['all'=> 'Enter a appropriate date-time']
            ]);
         }

         //check if end date_time is less than now date_time
         
         if($end_times < strtotime(date('Y-m-d h:i a')))
         {
            return response()->json([
                'success' => false,
                'errors' => ['all'=> 'Enter a appropriate date-time']
            ]);
         }

         $start_datetime=date('Y-m-d H:i:s',$start_times);

         $end_datetime=date('Y-m-d H:i:s',$end_times);

        //  dd($end_datetime);

        $data=[
            'business_id' => $business_id,
            'title' => $request->code_name,
            'discount' => $request->value,
            'discount_type' => $request->type,
            'uses_limit' => $request->uses_limit,
            'start_date' => $start_datetime,
            'end_date' => $end_datetime,
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('promocodes')->insert($data);

        return response()->json([
            'success' => true,
        ]);


    }

    public function promoEdit(Request $request)
    {
        $p_id =  base64_decode($request->id);

        $promocode=DB::table('promocodes')->where('id',$p_id)->first();

        return view('superadmin.settings.promocode.edit',compact('promocode'));
    }

    public function promoUpdate(Request $request)
    {
        $p_id=base64_decode($request->p_id);
        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;
        $rules= [
            'code_name'    => 'required|regex:/^(?=[A-Z])[A-Z0-9]+$/u|min:3|max:10',
            'type'  => 'required|in:percentage,fixed_amount',
            'value' => 'required',
            'uses_limit' => 'required|integer|min:1',
            'start_date' =>  'required|date',
            'end_date' =>'required|date',
            'start_time'  => 'required|date_format:h:i a',
            'end_time'  => 'required|date_format:h:i a',
         ];

         $custom=[
             'value.numeric' => 'Value should be numeric or decimal',
             'value.integer' => "Value should must be numeric",
             'code_name.regex' => 'Code Name should be Alphanumeric',
             'uses_limit.integer' => 'Uses Limit must be numeric'
         ];
        
         $validator = Validator::make($request->all(), $rules, $custom);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }

         // check if promocode already exist
         $promocount=DB::table('promocodes')
         ->where(DB::raw('BINARY `title`'),$request->code_name)
         ->where(['is_deleted'=>0])
         ->whereNotIn('id',[$p_id])
         ->count();

         if($promocount > 0)
         {
            return response()->json([
                'success' => false,
                'errors' => ['code_name'=> 'Code Name is Already Exist']
            ]);
         }

         if($request->type=='percentage')
         {
            //  dd($request->type);
            $rules=[
                'value' => 'numeric|min:1|max:5'
            ];
            $validator = Validator::make($request->all(), $rules,$custom);
          
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
            
         }
         else if($request->type=='fixed_amount')
         {
            $rules=[
                'value' => 'integer|min:1|max:10'
            ];
            
            $validator = Validator::make($request->all(), $rules,$custom);
          
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
         }

         $start_date=date('Y-m-d',strtotime($request->start_date));

         $end_date=date('Y-m-d',strtotime($request->end_date));

         $start_times=strtotime($start_date.' '.$request->start_time);

         $end_times=strtotime($end_date.' '.$request->end_time);

         $diff = $end_times - $start_times;

         if($diff<=0)
         {
            return response()->json([
                'success' => false,
                'errors' => ['all'=> 'Enter a appropriate date-time']
            ]);
         }

         //check if end date_time is less than now date_time
         
         if($end_times < strtotime(date('Y-m-d h:i a')))
         {
            return response()->json([
                'success' => false,
                'errors' => ['all'=> 'Enter a appropriate date-time']
            ]);
         }

         $start_datetime=date('Y-m-d H:i:s',$start_times);

         $end_datetime=date('Y-m-d H:i:s',$end_times);

        //  dd($end_datetime);

        $data=[
            'title' => $request->code_name,
            'discount' => $request->value,
            'discount_type' => $request->type,
            'uses_limit' => $request->uses_limit,
            'start_date' => $start_datetime,
            'end_date' => $end_datetime,
            'updated_by' => $user_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        DB::table('promocodes')->where('id',$p_id)->update($data);

        if($end_times > strtotime(date('Y-m-d h:i a')))
        {
            DB::table('promocodes')
                    ->where(['id'=>$p_id,'is_expired'=>1])
                    ->update([
                        'status' => 1,
                        'is_expired'=>0,
                        'updated_by' => $user_id,
                        'updated_at' => date('Y-m-d H:i:s')
                        ]);
        }

        return response()->json([
            'success' => true,
        ]);


    }

    public function promoDelete(Request $request)
    {
        $id=base64_decode($request->id);
        // dd($id);
        DB::table('promocodes')->where(['id'=>$id])->update([
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => Auth::user()->id
        ]);

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function promoStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            DB::table('promocodes')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('promocodes')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }
    
    public function holidays(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $holiday_master=DB::table('holiday_masters')
                    ->where('business_id',$business_id);
                    if($request->get('from_date') !=""){
                        $holiday_master->whereDate('date','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $holiday_master->whereDate('date','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if($request->get('type') !=""){
                        $holiday_master->where('type',$request->get('type'));
                    }
                    if(is_numeric($request->get('holiday_id'))){
                        $holiday_master->where('id',$request->get('holiday_id'));
                    }
        $items = $holiday_master->orderBy('date')->paginate(10);

        $holidays=DB::table('holiday_masters')->orderBy('date')->where('business_id',$business_id)->get();
        
        if($request->ajax())
            return view('superadmin.settings.holiday.ajax',compact('items','holidays'));
        else
            return view('superadmin.settings.holiday.index',compact('items','holidays'));
    }

    public function holidayStore(Request $request)
    {
        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;
        $rules= [
            'name'    => 'required|regex:/^[a-zA-Z][A-Za-z\/()\' ]+$/u|min:2|max:255',
            'date'    =>  'required|date',
         ];

         $custom=[
             'name.regex' => 'Name should be String',
         ];
        
         $validator = Validator::make($request->all(), $rules, $custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         // check if Holiday name already exist
         $holidaycount=DB::table('holiday_masters')->where(DB::raw('BINARY `name`'),$request->name)->count();

         if($holidaycount > 0)
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['name'=> 'Holiday Name is Already Exist !!']
            ]);
         }

         if(date('Y')!=date('Y',strtotime($request->date)))
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['date'=> 'Date Must be in Current year !!']
            ]);
         }

        //  dd($end_datetime);

        $data=[
            'business_id' => $business_id,
            'name' => $request->name,
            'date' => date('Y-m-d',strtotime($request->date)),
            'type' => 'custom',
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('holiday_masters')->insert($data);

        return response()->json([
            'fail' => false,
        ]);


    }

    public function holidayEdit(Request $request)
    {
        $id=base64_decode($request->id);

        if ($request->isMethod('get'))
        {
            $data = DB::table('holiday_masters')
            ->where(['id' =>$id])        
            ->first(); 
        
            return response()->json([                
                'result' => $data
            ]);
        }

        $business_id =Auth::user()->business_id;
        $user_id =  Auth::user()->id;
        $rules= [
            'name'    => 'required|regex:/^[a-zA-Z][A-Za-z\/()\' ]+$/u|min:2|max:255',
            'date'    =>  'required|date',
         ];

         $custom=[
             'name.regex' => 'Name should be String',
         ];
        
         $validator = Validator::make($request->all(), $rules, $custom);
          
         if ($validator->fails()){
             return response()->json([
                 'fail' => true,
                 'error_type' => 'validation',
                 'errors' => $validator->errors()
             ]);
         }

         // check if Holiday name already exist
         $holidaycount=DB::table('holiday_masters')
                        ->where(DB::raw('BINARY `name`'),$request->name)
                        ->whereNotIn('id',[$id])
                        ->count();

         if($holidaycount > 0)
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['name'=> 'Holiday Name is Already Exist !!']
            ]);
         }

         if(date('Y')!=date('Y',strtotime($request->date)))
         {
            return response()->json([
                'fail' => false,
                'error_type' => 'validation',
                'errors' => ['date'=> 'Date Must be in Current year !!']
            ]);
         }

         DB::table('holiday_masters')->where(['id'=>$id])->update([
             'name' => $request->name,
             'date' => date('Y-m-d',strtotime($request->date)),
             'updated_by' => $user_id,
             'updated_at' => date('Y-m-d H:i:s')
         ]);

         return response()->json([
            'fail' => false,
         ]);

    }

    public function holidayDelete(Request $request)
    {
        $id=base64_decode($request->id);
        // dd($id);
        DB::table('holiday_masters')->where(['id'=>$id])->delete();

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function holidayStatus(Request $request)
    {
        $id=base64_decode($request->id);
        $type = base64_decode($request->type);
        // dd($id);

        if($type=='active')
        {
            DB::table('holiday_masters')->where(['id'=>$id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }
        else
        {
            DB::table('holiday_masters')->where(['id'=>$id])->update([
                'status' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
            ]);
        }

        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'User deleted successfully');
        // }
        return response()->json([
            'status' => 'ok',
            'type' => $type
        ]);
    }

    //bill generation 

    public function customers_list()
    {
        $business_id = Auth::user()->business_id;
        $array_result=[];
        $items = DB::table('users as u')
        ->select('u.id')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'customer','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])->get();
        foreach($items as $item){
            $array_result[]=$item->id;
        }
        // dd($array_result);
        return $array_result;
    }

    public function report_list(){
        $customers=$this->customers_list();
        // $array_result=[];

        $today_date=date('Y-m-d');

        $start_date=date('Y-m-d',strtotime('-15 days'));

        // $start_date="2021-04-07";

        // $today_date="2021-04-22";

        // dd($start_date);

        // dd($array_result);

        $api_array=$this->apiPrice($start_date,$today_date);

        // dd($api_array);

        if(count($api_array)>0)
        {
            foreach($api_array as $record)
            {
                $billing_r=DB::table('billings')
                    ->where(['business_id'=>$record['business_id']])
                    ->whereDate('start_date','=',$start_date)
                    ->whereDate('end_date','=',$today_date)
                    ->first();
                if($billing_r==NULL)
                {
                    $billing_data=[
                    'parent_id'   => $record['parent_id'],
                    'business_id' => $record['business_id'],
                    'user_id'     => $record['business_id'],
                    'invoice_id'  => 'invoice_no'.Str::random(10),
                    'start_date'  => date('Y-m-d h:i:s',strtotime($start_date)),
                    'end_date'  => date('Y-m-d h:i:s',strtotime($today_date)),
                    'created_at'=> date('Y-m-d h:i:s'),
                    ];
                    $bill=DB::table('billings')->insertGetId($billing_data);

                    $billing_item_data=[
                    'billing_id'  => $bill,
                    'parent_id'   => $record['parent_id'],
                    'business_id' => $record['business_id'],
                    'user_id'     => $record['business_id'],
                    'service_id'  => $record['service_id'],
                    'service_name'=> $record['service_name'],
                    'service_item_number' =>1,
                    'quantity'    => $record['qty'],
                    'price'       =>$record['price'],
                    'created_at'=> date('Y-m-d h:i:s'),
                    'updated_at'=> date('Y-m-d h:i:s')
                    ];
                    $bill_items=DB::table('billing_items')->insert($billing_item_data);
                }
                else{

                    $billing_item_data1=[
                        'billing_id'  => $billing_r->id,
                        'parent_id'   => $record['parent_id'],
                        'business_id' => $record['business_id'],
                        'user_id'     => $record['business_id'],
                        'service_id'  => $record['service_id'],
                        'service_name'=> $record['service_name'],
                        'service_item_number' =>1,
                        'quantity'    => $record['qty'],
                        'price'       =>$record['price'],
                        'created_at'=> date('Y-m-d h:i:s'),
                        'updated_at'=> date('Y-m-d h:i:s')
                    ];
                    $bill_items1=DB::table('billing_items')->insert($billing_item_data1);
                }
            }
        }

        if(count($api_array) >0)
        {
            foreach($customers as $cust_id)
            {
                $billing_record=DB::table('billings as b')
                    ->select(DB::raw('sum(bi.price) as total_price'))
                    ->join('billing_items as bi','b.id','=','bi.billing_id')
                    ->where(['b.business_id'=>$cust_id])
                    ->first();
                if($billing_record!=NULL)
                {
                    DB::table('billings as b')
                    ->where(['b.business_id'=>$cust_id])
                    ->whereDate('b.start_date','=',$start_date)
                    ->whereDate('b.end_date','=',$today_date)
                    ->update([
                        'total_amount'=> $billing_record->total_price,
                        'updated_at'=> date('Y-m-d h:i:s')
                    ]);
                }
            }
        }

        dd($api_array);
        // dd($array_result);
    }

    public function apiPrice($start_date,$end_date)
    {
        // $parent_id=Auth::user()->business_id;
        $customers=$this->customers_list();

        $array_result=[];
        foreach($customers as $cust_id)
        {
            
            $aadhar=DB::table('aadhar_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($aadhar!=NULL)
            {
                $array_result[]=['parent_id'=>$aadhar->parent_id,'business_id'=>$cust_id,'service_id'=>$aadhar->service_id,'service_name'=>$aadhar->service_name,'price'=>$aadhar->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$aadhar->no_of_hits];
            }

            $pan=DB::table('pan_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($pan!=NULL)
            {
                $array_result[]=['parent_id'=>$pan->parent_id,'business_id'=>$cust_id,'service_id'=>$pan->service_id,'service_name'=>$pan->service_name,'price'=>$pan->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$pan->no_of_hits];
            }

            $voter_id=DB::table('voter_id_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_reference'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($voter_id!=NULL)
            {
                $array_result[]=['parent_id'=>$voter_id->parent_id,'business_id'=>$cust_id,'service_id'=>$voter_id->service_id,'service_name'=>$voter_id->service_name,'price'=>$voter_id->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$voter_id->no_of_hits];
            }
        
            $rc=DB::table('rc_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($rc!=NULL)
            {
                $array_result[]=['parent_id'=>$rc->parent_id,'business_id'=>$cust_id,'service_id'=>$rc->service_id,'service_name'=>$rc->service_name,'price'=>$rc->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$rc->no_of_hits];
            }

            $dl=DB::table('dl_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($dl!=NULL)
            {
                $array_result[]=['parent_id'=>$dl->parent_id,'business_id'=>$cust_id,'service_id'=>$dl->service_id,'service_name'=>$dl->service_name,'price'=>$dl->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$dl->no_of_hits];
            }

            $passport=DB::table('passport_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($passport!=NULL)
            {
                $array_result[]=['parent_id'=>$passport->parent_id,'business_id'=>$cust_id,'service_id'=>$passport->service_id,'service_name'=>$passport->service_name,'price'=>$passport->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$passport->no_of_hits];
            }

            $bank=DB::table('bank_account_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','a.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($bank!=NULL)
            {
                $array_result[]=['parent_id'=>$bank->parent_id,'business_id'=>$cust_id,'service_id'=>$bank->service_id,'service_name'=>$bank->service_name,'price'=>$bank->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$bank->no_of_hits];
            }

            $gst=DB::table('gst_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();

            if($gst!=NULL)
            {
                $array_result[]=['parent_id'=>$gst->parent_id,'business_id'=>$cust_id,'service_id'=>$gst->service_id,'service_name'=>$gst->service_name,'price'=>$gst->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$gst->no_of_hits];
            }

            $telecom=DB::table('telecom_check as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name',DB::raw('sum(a.price) as total_price'),DB::raw('count(a.service_id) as no_of_hits'))
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'customer','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->groupBy('a.service_id')
            ->first();
            
            if($telecom!=NULL)
            {
                $array_result[]=['parent_id'=>$telecom->parent_id,'business_id'=>$cust_id,'service_id'=>$telecom->service_id,'service_name'=>$telecom->service_name,'price'=>$telecom->total_price,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>$telecom->no_of_hits];
            }            
        }

       return $array_result;
    }

    public function user_businesses()
    {

        DB::beginTransaction();
        try{

            $business_id=Auth::user()->business_id;

            $customers=DB::table('users')->where(['parent_id'=>$business_id,'user_type'=>'customer'])->get();

            $cust_arr_id=[];

            foreach($customers as $customer)
            {
                $cust_arr_id[]=$customer->id;
            }

            // dd($cust_arr_id);

            DB::table('user_businesses')->whereIn('business_id',$cust_arr_id)->update(['country_id'=>101,'country_name'=>'India']);

            $cust_business=DB::table('user_businesses')
                            ->whereIn('business_id',$cust_arr_id)
                            ->get();
            //for Admin
            foreach($cust_business as $business)
            {
                if($business->state_id!=NULL && $business->state_name==NULL)
                {
                    $state=DB::table('states')->where('id',$business->state_id)->first();
                    if($business->city_name!=NULL)
                    {
                        // if($state->id==10)
                        // {
                        //     $city=DB::table('cities')->where('id',$state->id)->first();
                        // }
                        // else
                        // {
                        $city=DB::table('cities')->where('name',$business->city_name)->first();
                        // }
                        DB::table('user_businesses')
                            ->where(['id'=>$business->id])
                            ->update([
                                'state_id' =>$state->id,
                                'state_name' => $state->name,
                                'city_id' => $city->id,
                                'city_name' => $city->name
                            ]);
                    }
                    else
                    {
                        $city=DB::table('cities')->where('state_id',$state->id)->first();
                        DB::table('user_businesses')
                            ->where(['id'=>$business->id])
                            ->update([
                                'state_id' =>$state->id,
                                'state_name' => $state->name,
                                'city_id' => $city->id,
                                'city_name' => $city->name
                            ]);

                    }
                }
                else if($business->state_id!=NULL && $business->state_name==NULL)
                {
                    $state=DB::table('states')->where('name',$business->state_name)->first();
                    if($business->city_name!=NULL)
                    {
                        // if($state->id==10)
                        // {
                        //     $city=DB::table('cities')->where('id',$state->id)->first();
                        // }
                        // else
                        // {
                        $city=DB::table('cities')->where('name',$business->city_name)->first();
                        // }
                        DB::table('user_businesses')
                            ->where(['id'=>$business->id])
                            ->update([
                                'state_id' =>$state->id,
                                'state_name' => $state->name,
                                'city_id' => $city->id,
                                'city_name' => $city->name
                            ]);
                    }
                    else
                    {
                        $city=DB::table('cities')->where('state_id',$state->id)->first();
                        DB::table('user_businesses')
                            ->where(['id'=>$business->id])
                            ->update([
                                'state_id' =>$state->id,
                                'state_name' => $state->name,
                                'city_id' => $city->id,
                                'city_name' => $city->name
                            ]);

                    }
                }
                else if($business->state_id!=NULL && $business->state_name!=NULL)
                {
                    $state=DB::table('states')->where('id',$business->state_id)->first();
                    if($business->city_name!=NULL)
                    {
                        // if($state->id==10)
                        // {
                        //     $city=DB::table('cities')->where('id',$state->id)->first();
                        // }
                        // else
                        // {
                        $city=DB::table('cities')->where('name',$business->city_name)->first();
                        // }
                        DB::table('user_businesses')
                            ->where(['id'=>$business->id])
                            ->update([
                                'state_id' =>$state->id,
                                'state_name' => $state->name,
                                'city_id' => $city->id,
                                'city_name' => $city->name
                            ]);
                    }
                    else
                    {
                        $city=DB::table('cities')->where('state_id',$state->id)->first();
                        DB::table('user_businesses')
                            ->where(['id'=>$business->id])
                            ->update([
                                'state_id' =>$state->id,
                                'state_name' => $state->name,
                                'city_id' => $city->id,
                                'city_name' => $city->name
                            ]);

                    }
                }
                // else
                // {
                //     $state=DB::table('states')->where('id',10)->first();
                // }
                // $country=DB::table('countries')->where('id',$state->country_id)->first();
                
            }

            //for COCs
            DB::table('user_businesses')
                    ->whereNotIn('business_id',$cust_arr_id)
                    ->update([
                        'country_id' => 101,
                        'country_name' => 'India'
                    ]);
            
            $client_business=DB::table('user_businesses')
                            ->whereNotIn('business_id',$cust_arr_id)
                            ->get();
            if(count($client_business)>0)
            {
                foreach($client_business as $business)
                {
                    //states
                    // dd($business);
                    $state_name=NULL;
                    $state_id=NULL;
                    if($business->state_id!=NULL && $business->state_name==NULL){
                        $state_id=$business->state_id;
                        $state=DB::table('states')->where('id',$business->state_id)->first();

                        if($business->city_id!=NULL && $business->city_name==NULL)
                        {
                            $city=DB::table('cities')->where('id',$business->city_id)->first();
                            // dd($city);
                        }
                        else if($business->city_id==NULL && $business->city_name!=NULL)
                        {
                            $city_name=$business->city_name;
                            if($business->city_name=='Rohtaas')
                            {
                                $city_name='Karnal';
                            }
                            $city=DB::table('cities')->where('name',$city_name)->first();
                            
                        }
                        else if($business->city_id!=NULL && $business->city_name!=NULL)
                        {
                            $city=DB::table('cities')->where('id',$business->city_id)->first();
                        }
                        else
                        {
                            $city=DB::table('cities')->where('state_id',$state_id)->first();
                        }
                    
                    DB::table('user_businesses')
                            ->where('id',$business->id)
                            ->update([
                                'country_id' => 101,
                                'country_name' => 'India',
                                'state_id'  => $state_id,
                                'state_name' => $state_name,
                                'city_id'   => $city->id,
                                'city_name' => $city->name
                            ]);
                    }
                    else if($business->state_id==NULL && $business->state_name!=NULL)
                    {
                        if(stripos($business->state_name,'UP')!==false || stripos($business->state_name,'Noida')!==false || stripos($business->state_name,'U.P')!==false)
                        {
                            $state_name='Uttar Pradesh';
                        }
                        else
                        {
                            $state_name = $business->state_name;
                        }

                        // dd($state_name);
                    
                        $state=DB::table('states')->where('name',$state_name)->first(); 
                        $state_id=$state->id;      
                        
                        if($business->city_id!=NULL && $business->city_name==NULL)
                        {
                            $city=DB::table('cities')->where('id',$business->city_id)->first();
                            // dd($city);
                        }
                        else if($business->city_id==NULL && $business->city_name!=NULL)
                        {
                            $city_name=$business->city_name;
                            if($business->city_name=='Rohtaas')
                            {
                                $city_name='Karnal';
                            }
                            $city=DB::table('cities')->where('name',$city_name)->first();
                            
                        }
                        else if($business->city_id!=NULL && $business->city_name!=NULL)
                        {
                            $city=DB::table('cities')->where('id',$business->city_id)->first();
                        }
                        else
                        {
                            $city=DB::table('cities')->where('state_id',$state_id)->first();
                        }
                    
                        DB::table('user_businesses')
                                ->where('id',$business->id)
                                ->update([
                                    'country_id' => 101,
                                    'country_name' => 'India',
                                    'state_id'  => $state_id,
                                    'state_name' => $state_name,
                                    'city_id'   => $city->id,
                                    'city_name' => $city->name
                                ]);
                    }
                    else if($business->state_id!=NULL && $business->state_name!=NULL)
                    {
                        $state=DB::table('states')->where('id',$business->state_id)->first();
                        $state_id=$state->id;

                        if($business->city_id!=NULL && $business->city_name==NULL)
                        {
                            $city=DB::table('cities')->where('id',$business->city_id)->first();
                            // dd($city);
                        }
                        else if($business->city_id==NULL && $business->city_name!=NULL)
                        {
                            $city_name=$business->city_name;
                            if($business->city_name=='Rohtaas')
                            {
                                $city_name='Karnal';
                            }
                            $city=DB::table('cities')->where('name',$city_name)->first();
                            
                        }
                        else if($business->city_id!=NULL && $business->city_name!=NULL)
                        {
                            $city=DB::table('cities')->where('id',$business->city_id)->first();
                        }
                        else
                        {
                            $city=DB::table('cities')->where('state_id',$state_id)->first();
                        }
                    
                        DB::table('user_businesses')
                                ->where('id',$business->id)
                                ->update([
                                    'country_id' => 101,
                                    'country_name' => 'India',
                                    'state_id'  => $state_id,
                                    'state_name' => $state_name,
                                    'city_id'   => $city->id,
                                    'city_name' => $city->name
                                ]);
                    }
                    // else
                    // {
                    //     $state=DB::table('states')->where('id',10)->first();
                    //     $state_id=$state->id;
                    // }

                    // dd($state);
                    
                    
                }
            }

            $client_business=DB::table('user_businesses')
                            ->whereNotIn('business_id',$cust_arr_id)
                            ->whereNull('state_name')
                            ->get();
            
            if(count($client_business)>0)
            {
                foreach($client_business as $business)
                {
                    $state=DB::table('states')->where('id',$business->state_id)->first();

                    DB::table('user_businesses')->where('id',$business->id)->update(['state_name'=>$state->name]);
                }

            }
            // state_id 10
            $client_business=DB::table('user_businesses')
                            // ->whereIn('business_id',$cust_arr_id)
                            ->where('state_id',10)
                            ->get();

            if(count($client_business)>0)
            {
                foreach($client_business as $business)
                {
                    // $cities=DB::table('cities')->where('state_id',$business->state_id)->get();
                    // $city_arr=[];
                    // foreach($cities as $city)
                    // {
                    //     $city_arr[]=$city->id;
                    // }

                    // dd($city_arr);
                    // dd($business->city_id);
                    // if($business->state_id==$business->city_id)
                    // {
                        
                        // dd($city_arr);

                        $city=DB::table('cities')->where('state_id',10)->first();

                        // dd($city);

                        DB::table('user_businesses')->where(['id'=>$business->id])->update([
                            'city_id'   => $city->id,
                            'city_name' => $city->name
                        ]);
                    // }
                }
            }
            // $clients=DB::table('users')
            //         ->whereIn('parent_id',$cust_arr_id)
            //         ->where('user_type','client')
            //         ->get();

            // dd($clients);
            // foreach($clients as $client)
            // {
            //     $client_business = DB::table('user_business')
            // }
            // foreach($customers as $customer)
            // {
            //     $cust_business=DB::table('user_businesses')
            //                     ->where(['business_id'=>$customer->id])
            //                     ->orwhere()
            //                     ->update([

            //                     ]);
            // }
            DB::commit();
            return 'done';
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
    }

    public function googleCalender()
    {
        $business_id = Auth::user()->business_id;
        DB::beginTransaction();
        try{
            // $data = array(
            //     'key'    => 'AIzaSyA3iQ4wljb-b4FlGKnj4BwlMJRj2DQTFRc',
            // );
            // $payload = json_encode($data);
            $apiURL = "https://www.googleapis.com/calendar/v3/calendars/en.indian%23holiday%40group.v.calendar.google.com/events?key=AIzaSyA3iQ4wljb-b4FlGKnj4BwlMJRj2DQTFRc";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
            // curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json')); // Inject the token into the header
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            // Attach encoded JSON string to the POST fields
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $resp = curl_exec ($ch);
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ($ch);

            // dd($resp);
            // dd($response_code);
                
            $array_data =  json_decode($resp,true);

            // dd($array_data['items']);
            // $array_result=[];
            DB::table('holiday_masters')->where('type','public')->delete();

            DB::table('holiday_masters')
                        ->where('type','custom')
                        ->whereYear('date','<>',date('Y'))
                        ->delete();
            foreach($array_data['items'] as $key => $item)
            {
                if(date('Y')==date('Y',strtotime($item["start"]['date'])))
                {
                    // $array_result[]=['summary'=>$item['summary'],'date'=>$item['start']['date']];

                    DB::table('holiday_masters')->insert([
                        'business_id' => $business_id,
                        'name' => $item['summary'],
                        'date' => $item['start']['date'],
                        'created_by' => $business_id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            DB::commit();
            // return 'Record Inserted Successfully';
            return response()->json([
                'success' => true
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function completeDateReportUpdate()
    {
        $business_id = Auth::user()->business_id;

        $customers=DB::table('users')->where(['parent_id'=>$business_id,'user_type'=>'customer'])->get();

        foreach($customers as $cust)
        {

            $reports=DB::table('reports')
                    ->where(['parent_id'=>$cust->id,'status'=>'completed'])
                    ->whereNull('complete_created_at')
                    ->get();        

            foreach($reports as $report)
            {
                DB::table('reports')->where(['id'=>$report->id])->update([
                    'complete_created_at' => $report->created_at
                ]);
            }
        }

        return 'Report Completed Date Updated Successfully';
       
    }

    public function billingPriceUpdate()
    {
        $billing_items=DB::table('billing_items')->where(['total_check_price'=>'0'])->get();

        foreach($billing_items as $item)
        {
            DB::table('billing_items')->where(['id'=>$item->id])->update([
                'total_check_price' => $item->price
            ]);
        }

        return 'Price Updated Successfully';
    }

    public function jafReferenceTypeUpdate()
    {
        $business_id = Auth::user()->business_id;

        $customers = DB::table('users')->where(['parent_id'=>$business_id,'user_type'=>'customer'])->orderBy('id','desc')->get();

        foreach($customers as $cust)
        {
            $jaf_data = DB::table('jaf_form_data as jf')
                        ->select('jf.*')
                        ->join('users as u','u.id','=','jf.candidate_id')
                        ->join('job_items as j','j.id','=','jf.job_item_id')
                        ->where(['u.parent_id'=>$cust->id,'u.user_type'=>'candidate','jf.service_id'=>17,'j.jaf_status'=>'filled'])
                        ->whereNotNULL('jf.form_data')
                        ->orderBy('id','asc')
                        ->get();
            // dd($jaf_data);
            
            if(count($jaf_data)>0)
            {
                foreach($jaf_data as $jd)
                {
                    $reference_type = NULL;
                    $input_data = $jd->form_data;

                    $input_data_array = json_decode($input_data,true);

                    // dd($input_data_array);

                    foreach($input_data_array as $key => $input)
                    {
                        $key_val = array_keys($input); $input_val = array_values($input);

                        if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                        {
                            if(stripos($input_val[0],'personal')!==false)
                            {
                                $reference_type = 'personal';

                            }
                            else if(stripos($input_val[0],'professional')!==false)
                            {
                                $reference_type = 'professional';
                            }
                        }

                    }
                    DB::table('jaf_form_data')
                            ->where(['id'=>$jd->id])
                            ->update(
                                [
                                    'reference_type' => $reference_type,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);

                }
            }
            
            
        }

        echo "Record Updated Successfully";
    }

    public function reportReferenceTypeUpdate()
    {
        $business_id = Auth::user()->business_id;

        $customers = DB::table('users')->where(['parent_id'=>$business_id,'user_type'=>'customer'])->orderBy('id','desc')->get();

        foreach($customers as $cust)
        {
            $report_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('users as u','u.id','=','ri.candidate_id')
                        ->where(['u.parent_id'=>$cust->id,'u.user_type'=>'candidate','ri.service_id'=>17])
                        ->whereNotNULL('ri.jaf_data')
                        ->orderBy('ri.id','asc')
                        ->get();
            // dd($report_items);
            
            if(count($report_items)>0)
            {
                foreach($report_items as $ri)
                {
                    $reference_type = NULL;
                    $input_data = $ri->jaf_data;

                    $input_data_array = json_decode($input_data,true);

                    // dd($input_data_array);

                    foreach($input_data_array as $key => $input)
                    {
                        $key_val = array_keys($input); $input_val = array_values($input);

                        if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                        {
                            if(stripos($input_val[0],'personal')!==false)
                            {
                                $reference_type = 'personal';

                            }
                            else if(stripos($input_val[0],'professional')!==false)
                            {
                                $reference_type = 'professional';
                            }
                        }

                    }
                    DB::table('report_items')
                            ->where(['id'=>$ri->id])
                            ->update(
                                [
                                    'reference_type' => $reference_type,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);

                }
            }
            
            
        }

        echo "Record Updated Successfully";
    }

    public function updateClientName()
    {
        $clients=DB::table('users')->where('user_type','client')->get();

        foreach($clients as $client)
        {
            DB::table('users')->where(['id'=>$client->id])->update([
                'name' => $client->first_name.' '.$client->middle_name.' '.$client->last_name
            ]);
        }

        echo "Client Name Updated Successfully";

    }

    public function updateClientDisplayId()
    {
        $users = DB::table('users as u')
                    ->select('u.*','ub.company_name')
                    ->join('user_businesses as ub','u.id','=','ub.business_id')
                    ->where('u.user_type','client')
                    ->get();

        if(count($users)>0)
        {
            foreach($users as $user)
            {
                $u_id = str_pad($user->id, 10, "0", STR_PAD_LEFT);
                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($user->company_name,0,4)))).'-'.$u_id;
        
                DB::table('users')->where('id',$user->id)->update([
                    'display_id' => $display_id
                ]);
            }
        }

        echo 'done';
       
    }

    public function updateUserDisplayId()
    {
        $users = DB::table('users as u')
                    ->select('u.*','ub.company_name')
                    ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                    ->where('u.user_type','user')
                    ->get();

        if(count($users)>0)
        {
            foreach($users as $user)
            {
                $u_id = str_pad($user->id, 10, "0", STR_PAD_LEFT);
                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($user->company_name,0,4)))).'-'.$u_id;
        
                DB::table('users')->where('id',$user->id)->update([
                    'display_id' => $display_id
                ]);
            }
        }

        echo 'done';
    }

    public function updateCustomerDisplayId()
    {
        $users = DB::table('users as u')
                    ->select('u.*','ub.company_name')
                    ->join('user_businesses as ub','u.id','=','ub.business_id')
                    ->where('u.user_type','customer')
                    ->get();

        if(count($users)>0)
        {
            foreach($users as $user)
            {
                $u_id = str_pad($user->id, 10, "0", STR_PAD_LEFT);
                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($user->company_name,0,4)))).'-'.$u_id;
        
                DB::table('users')->where('id',$user->id)->update([
                    'display_id' => $display_id
                ]);
            }
        }

        echo 'done';
       
    }

    public function updateFakeEmployment()
    {
        // $path = public_path().'/excel/';
        // $file_name =  'Employment Fake List-1638005423.xlsx';

        // $file = $this->createFileObject($path.$file_name);

        // // dd($file);
        // $parsed_array = Excel::toArray([], $file);
        // $imported_data = array_splice($parsed_array[0], 1);

        // // dd($imported_data);

        // if(count($imported_data) > 0)
        // {
        //     foreach ($imported_data as $value)
        //     {
        //         DB::table('fake_employment_lists')->insert([
        //             'company_name' => $value[0],
        //             'company_location' => stripos($value[1],'None Provided')!==false? NULL : $value[1],
        //             'company_details' => stripos($value[2],'None Provided')!==false? NULL : $value[2],
        //             'created_at' =>date('Y-m-d H:i:s')
        //         ]);
        //     }

        //     return 'inserted';
        // }

        $path = public_path().'/excel/';
        $file_name =  'fake Employement.xlsx';

        $file = $this->createFileObject($path.$file_name);

        // dd($file);
        $parsed_array = Excel::toArray([], $file);
        $imported_data = array_splice($parsed_array[0], 1);

        //dd($imported_data);

        // dd($imported_data);

        if(count($imported_data) > 0)
        {
            foreach ($imported_data as $value)
            {
                DB::table('fake_employment_lists')->insert([
                    'company_name' => $value[0],
                    //'company_location' => stripos($value[1],'None Provided')!==false? NULL : $value[1],
                    //'company_details' => stripos($value[2],'None Provided')!==false? NULL : $value[2],
                    'created_at' =>date('Y-m-d H:i:s')
                ]);
            }

            return 'inserted';
        }

    }

    public function updateFakeEducational()
    {
        // $path = public_path().'/excel/';
        // $file_name =  'Education Fake List-1638005416.xlsx';

        // $file = $this->createFileObject($path.$file_name);

        // $parsed_array = Excel::toArray([], $file);
        // $imported_data = array_splice($parsed_array[0],0);

        // // dd($imported_data);

        // if(count($imported_data) > 0)
        // {
        //     foreach ($imported_data as $value)
        //     {
        //         DB::table('fake_educational_lists')->insert([
        //             'board_or_university_name' => $value[1],
        //             'created_at' =>date('Y-m-d H:i:s')
        //         ]);
        //     }

        //     return 'inserted';
        // }

        $path = public_path().'/excel/';
        $file_name =  'Fake University.xlsx';

        $file = $this->createFileObject($path.$file_name);

        $parsed_array = Excel::toArray([], $file);
        $imported_data = array_splice($parsed_array[0],0);

        // dd($imported_data);

        if(count($imported_data) > 0)
        {
            foreach ($imported_data as $value)
            {
                DB::table('fake_educational_lists')->insert([
                    'board_or_university_name' => $value[1],
                    'created_at' =>date('Y-m-d H:i:s')
                ]);
            }

            return 'inserted';
        }
    }

    public function updateGenEmployment()
    {
        // $path = public_path().'/excel/';
        // $file_name =  'Employment Fake List-1638005423.xlsx';

        // $file = $this->createFileObject($path.$file_name);

        // // dd($file);
        // $parsed_array = Excel::toArray([], $file);
        // $imported_data = array_splice($parsed_array[0], 1);

        // // dd($imported_data);

        // if(count($imported_data) > 0)
        // {
        //     foreach ($imported_data as $value)
        //     {
        //         DB::table('fake_employment_lists')->insert([
        //             'company_name' => $value[0],
        //             'company_location' => stripos($value[1],'None Provided')!==false? NULL : $value[1],
        //             'company_details' => stripos($value[2],'None Provided')!==false? NULL : $value[2],
        //             'created_at' =>date('Y-m-d H:i:s')
        //         ]);
        //     }

        //     return 'inserted';
        // }

        $path = public_path().'/excel/';
        $file_name =  'geniune Employement.xlsx';

        $file = $this->createFileObject($path.$file_name);

        // dd($file);
        $parsed_array = Excel::toArray([], $file);
        $imported_data = array_splice($parsed_array[0], 1);

         //dd($imported_data);

        if(count($imported_data) > 0)
        {
            foreach ($imported_data as $value)
            {
                DB::table('fake_employment_lists')->insert([
                    'company_name' => $value[1],
                    'person_name'=>stripos($value[2],'None Provided')!==false || stripos($value[2],'NA')!==false? NULL : $value[2],
                    'person_email'=>stripos($value[3],'None Provided')!==false || stripos($value[2],'NA')!==false? NULL : $value[3],
                    'person_email'=>stripos($value[4],'None Provided')!==false || stripos($value[4],'NA')!==false? NULL : $value[4],
                    'company_location' => stripos($value[5],'None Provided')!==false || stripos($value[5],'NA')!==false? NULL : $value[5],
                    //'company_details' => stripos($value[2],'None Provided')!==false || stripos($value[1],'NA')!==false? NULL : $value[2],
                    'type' => 'genuine',
                    'created_at' =>date('Y-m-d H:i:s')
                ]);
            }

            return 'inserted';
        }

    }

    public function updateGenEducational()
    {
        // $path = public_path().'/excel/';
        // $file_name =  'Education Fake List-1638005416.xlsx';

        // $file = $this->createFileObject($path.$file_name);

        // $parsed_array = Excel::toArray([], $file);
        // $imported_data = array_splice($parsed_array[0],0);

        // // dd($imported_data);

        // if(count($imported_data) > 0)
        // {
        //     foreach ($imported_data as $value)
        //     {
        //         DB::table('fake_educational_lists')->insert([
        //             'board_or_university_name' => $value[1],
        //             'created_at' =>date('Y-m-d H:i:s')
        //         ]);
        //     }

        //     return 'inserted';
        // }

        $path = public_path().'/excel/';
        $file_name =  'Geniune University list.xlsx';

        $file = $this->createFileObject($path.$file_name);

        $parsed_array = Excel::toArray([], $file);
        $imported_data = array_splice($parsed_array[0],0);

        // dd($imported_data);

        if(count($imported_data) > 0)
        {
            foreach ($imported_data as $value)
            {
                DB::table('fake_educational_lists')->insert([
                    'board_or_university_name' => $value[1],
                    'type' => 'genuine',
                    'created_at' =>date('Y-m-d H:i:s')
                ]);
            }

            return 'inserted';
        }
    }


    public function createFileObject($url){
  
        $path_parts = pathinfo($url);
  
        $newPath = $path_parts['dirname'] . '/tmp-files/';
        if(!is_dir ($newPath)){
            mkdir($newPath, 0777);
        }
  
        $newUrl = $newPath . $path_parts['basename'];
        copy($url, $newUrl);
        // $imgInfo = getimagesize($newUrl);
  
        $file = new UploadedFile(
            $newUrl,
            $path_parts['basename'],
            NULL,
            filesize($url),
            true,
            TRUE
        );
  
        return $file;
    }

    // Updating the name for user type user where first_name='Abhijit' & last_name='Ahluwalia'

    public function update_user_name()
    {
        $users = DB::table('users')->where(['user_type'=>'user','first_name'=>'Abhijit','last_name'=>'Ahluwalia'])->get();

        if(count($users) > 0)
        {
            foreach($users as $user)
            {
                $arr = [];

                $arr = explode(' ',trim($user->name));

                $first_name = $arr[0];

                $last_name = end($arr);

                DB::table('users')->where(['id'=>$user->id])->update([
                    'first_name' =>$first_name,
                    'last_name' => count($arr)>1 ? $last_name : NULL
                ]);
            }
        }

        echo 'done';
    }

    // Updating the name for extra spacing
    public function update_extra_space_name()
    {
        $users = DB::table('users')->get();

        if(count($users)>0)
        {
            foreach($users as $user)
            {

                //^\s+      # Match whitespace at the start of the string
                //|         # or
                //\s+$      # Match whitespace at the end of the string
                //|         # or
                //\s+(?=\s) # Match whitespace if followed by another whitespace character
                
                $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $user->name));

                // if($user->id==103)
                //     dd($name);

                DB::table('users')->where('id',$user->id)->update(['name'=>$name]);
            }

            echo 'done';
        }
    }

    public function apiUsageDate()
    {
        $from_date = date('Y-m-d',strtotime('01 march 2022'));

        $to_date = date('Y-m-t',strtotime('01 march 2022'));

        $aadhar=DB::table('aadhar_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_reference'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $pan=DB::table('pan_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $voter_id=DB::table('voter_id_checks as a')
                    ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_reference'=>'API'])
                    ->whereDate('a.created_at','>=',$from_date)
                    ->whereDate('a.created_at','<=',$to_date)
                    ->groupBy('a.service_id')
                    ->get();

        $rc=DB::table('rc_checks as a')
                    ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_type'=>'API'])
                    ->whereDate('a.created_at','>=',$from_date)
                    ->whereDate('a.created_at','<=',$to_date)
                    ->groupBy('a.service_id')
                    ->get();

        $dl=DB::table('dl_checks as a')
                    ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['source_type'=>'API'])
                    ->whereDate('a.created_at','>=',$from_date)
                    ->whereDate('a.created_at','<=',$to_date)
                    ->groupBy('a.service_id')
                    ->get();

        $passport=DB::table('passport_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $bank=DB::table('bank_account_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $gst=DB::table('gst_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $telecom=DB::table('telecom_check as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

       $e_court=DB::table('e_court_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $upi=DB::table('upi_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $cin=DB::table('cin_checks as a')
                        ->select('s.id as service_id','s.name',DB::raw('count(a.service_id) as no_of_hits'),DB::raw('sum(a.price) as total_price'))
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['source_type'=>'API'])
                        ->whereDate('a.created_at','>=',$from_date)
                        ->whereDate('a.created_at','<=',$to_date)
                        ->groupBy('a.service_id')
                        ->get();

        $items = $aadhar->merge($pan)
                        ->merge($voter_id)
                        ->merge($rc)
                        ->merge($dl)
                        ->merge($passport)
                        ->merge($bank)
                        ->merge($gst)
                        ->merge($telecom)
                        ->merge($e_court)
                        ->merge($upi)
                        ->merge($cin);

        dd($items);
    }
    
}
