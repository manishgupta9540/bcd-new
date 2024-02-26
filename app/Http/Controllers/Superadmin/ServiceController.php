<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $services = DB::table('services')
                    ->where('business_id',NULL)
                    ->get();
        return view('superadmin.services.index', compact('services'));

    }

    /**
     * Show the verifictions config.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function view($id, Request $request)
    {   
        $get_id = base64_decode($id);

        $service = DB::table('services')->where(['id'=>$get_id])->first();

        $form_input_masters = DB::table('form_input_masters')->get();

        $form_inputs = DB::table('service_form_inputs as sf')
            ->select('sf.label_name','sf.id','fm.name as type','sf.is_report_output','sf.is_executive_summary') 
            ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')       
            ->where(['sf.service_id' => $get_id,'status'=>1])
            ->whereNull('sf.reference_type')
            ->whereNotIn('sf.label_name',['Mode of Verification','Remarks'])        
            ->get(); 

        return view('superadmin.services.view', compact('service','form_input_masters','form_inputs'));

    }

     /**
     * add form input
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save_form_input(Request $request)
    {
        // dd($request);

        $rules = [
            'type'          => 'required',                
            'label_name'    => 'required',
            'mandatory'    => 'required',
            'report_output'    => 'required',
            'executive_output'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

        $service_id = $request->input('service_id');
        DB::beginTransaction();
        try{
            //check 
            $check = DB::table('service_form_inputs')
            ->select('*')        
            ->where(['service_id' => $service_id,'label_name'=>$request->input('label_name')])        
            ->first();         

            if($check ===null)
            {
                $storeData =[
                "service_id"               => $service_id,
                "form_input_type_id"       => $request->input('type'),
                "label_name"               => $request->input('label_name'),  
                "is_mandatory"             => $request->input('mandatory'),   
                "is_report_output"         => $request->input('report_output'),
                "is_executive_summary"     => $request->input('executive_output'),                         
                "created_at"               => date('Y-m-d H:i:s')];
                
                //insert data
                $insertedID = DB::table('service_form_inputs')->insertGetId( $storeData );    
                    
                $input_data = DB::table('service_form_inputs as sf')
                ->select('sf.label_name','sf.id','fm.name as type','sf.is_report_output as report_output','sf.is_executive_summary as executive_summary') 
                ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')       
                ->where(['sf.id' => $insertedID])        
                ->first();  
                DB::commit();
                return response()->json([
                    'fail' => false,
                    'data' => $input_data,
                    'error'=>'no'
                ]);
            }else{
            
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Label name is already created for this service!'
                ]);

            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
    }

    public function serviceFormInputEdit(Request $request)
    {
        $input_id = $request->input('input_id');

            $data = DB::table('service_form_inputs as sf')
            ->select('sf.label_name','sf.id as input_id','sf.is_mandatory','fm.name as type','fm.id','sf.is_report_output','sf.is_executive_summary') 
            ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')
            ->where(['sf.id' => $input_id])        
            ->first(); 
        
            return response()->json([                
                'result' => $data
            ]);
        
    }

    //update forminput item
    public function serviceFormInputUpdte(Request $request)
    {
        $rules = [
            'type'          => 'required',                
            'label_name'    => 'required',
            'mandatory'    => 'required',
            'executive_output'    => 'required',
            'report_output'    => 'required',
        ];
        
        $input_id = $request->input('input_id');

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);

        $service_id = $request->input('service_id');
        DB::beginTransaction();
        try{
            //check 
            $check = DB::table('service_form_inputs')
            ->select('*')        
            ->where(['service_id' => $service_id,'label_name'=>$request->input('label_name')])
            ->whereNotIn('id',[$input_id])        
            ->first();         

            if($check ===null)
            {
                $storeUpdate =[
                "form_input_type_id"    => $request->input('type'),
                "label_name"            => $request->input('label_name'),
                "is_mandatory"          => $request->input('mandatory'),   
                "is_report_output"      => $request->input('report_output'),     
                "is_executive_summary"  => $request->input('executive_output'),       
                "updated_at"            => date('Y-m-d H:i:s')];
                
                //insert data
                $is_saved = DB::table('service_form_inputs')
                            ->where('id',$input_id)
                            ->update($storeUpdate);
                
                $data = DB::table('service_form_inputs as sf')
                            ->select('sf.label_name','sf.id as input_id','sf.is_mandatory','fm.name as type','fm.id','sf.is_report_output as report_output','sf.is_executive_summary as executive_summary') 
                            ->join('form_input_masters as fm','fm.id','=','sf.form_input_type_id')
                            ->where(['sf.id' => $input_id])        
                            ->first(); 
                DB::commit();
                return response()->json([
                    'fail' => false,
                    'message' => 'updated',
                    'error'=>'no',
                    'data'=>$data
                ]);
            }
            else{
            
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Label name is already created for this service!'
                ]);

            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
    }

    // save new service
    public function save_service(Request $request)
    {
        $is_common=0;
         $rules = [
            'name'                => 'required',                
            'multiple_type'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);
        DB::beginTransaction();
        try
        {
            //check 
            $check = DB::table('services')
                    ->select('*')        
                    ->where(['name'=>$request->input('name')])        
                    ->first();         

            if($check ===null)
            {
                $storeData =[            
                "name"                   => $request->input('name'),
                "is_multiple_type"       => $request->input('multiple_type'),                          
                "created_at"             => date('Y-m-d H:i:s'),
                "is_common"              => 1,
                "verification_type"      => 'Manual'
                ];

                //insert data
                $insertedID = DB::table('services')->insertGetId( $storeData );   
                
                DB::table('services')->where('id',$insertedID)->update([
                    'sort_number' => $insertedID
                ]);
                
                DB::commit();
                return response()->json([
                    'fail' => false,
                    'error'=>'no'
                ]);
            }else{
            
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Service name is already created !'
                ]);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
    }

    //service edit input
    public function serviceEdit(Request $request)
    {
        $service_id = $request->input('service_id');
        
        if ($request->isMethod('get'))
        {
            $data = DB::table('services as a')
            ->select('a.name','a.id as service_id','a.is_multiple_type') 
            ->where(['a.id' => $service_id])        
            ->first(); 
        
            return response()->json([                
                'result' => $data
            ]);
        }

         $rules = [
            'name'                => 'required',                
            'is_multiple_type'    => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);
        
        DB::beginTransaction();
        try
        {

            $check = DB::table('services')
                    ->select('*')        
                    ->where(['name'=>$request->input('name')])  
                    ->whereNotIn('id',[$service_id])      
                    ->first();       

            if($check ===null)
            {
                $storeUpdate =[
                
                "name"                  => $request->input('name'),
                "is_multiple_type"      => $request->input('is_multiple_type'), 
                "updated_at"            => date('Y-m-d H:i:s')];
                
                //insert data
                $data = DB::table('services')
                        ->where('id',$service_id)
                        ->update($storeUpdate);
                
                DB::commit();
                return response()->json([
                    'fail' => false,
                    'message' => 'updated',
                    'error'=>'no'
                ]);
            }
            else{
            
                return response()->json([
                    'fail' => true,
                    'error' => 'yes',
                    'message'=>'Service name is already created !'
                ]);

            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

}
