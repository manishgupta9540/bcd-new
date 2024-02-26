<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class JafController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

     // candidate Jaf form
     public function candidateJafForm(Request $request)
     {
      
        $validator = Validator::make($request->all(), [
            'candidate_id'  => 'required',
            'sla_id'        => 'required',
        ]);
 
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                     'message'=>'The given data was invalid.',
                                     'errors'=> $validator->errors()], 200);
        }
 
        // store variable
        $candidate_id  = $request->get('candidate_id');
        $sla_id = 13;
        $services = [];
        $form_inputs=[];

        $check_sla = DB::table('jobs as j')
        ->select('j.*')
        ->where(['j.sla_id'=>$sla_id])
        ->first();
 
        // check job exit or not
        if($check_sla ===null)
        {  
           return response()->json(['status' => 'error','error_message'=>'It seems like link is not exist!, please contact to your admin'], 200);
        }
         
        $sla_items = DB::table('customer_sla_items as csi')
                ->select('csi.*')            
                ->where(['csi.sla_id'=>$sla_id])
                ->get();
        
        foreach($sla_items as $item){
            
                //get service name based on servcie ID
                $service_name = $this->get_service_name($item->service_id);
                $form_inputs = $this->get_service_form_inputs($item->service_id);

                $services[] = [
                    'service_id'    => $item->service_id,
                    'service_name'  => $service_name,
                    'form_inputs'   => $form_inputs
                    ];

        }

         //response data
         $response =['status'=>'success',
                        'message' =>'',
                        'data'    =>$services
                    
                    ]; 
 
         return response()->json($response, 200);
        
     }

     //Candidate Jaf Form Save
     public function candidateJafFormSave(Request $request)
     {
        //  dd($request);
        $validator = Validator::make($request->all(), [
            'candidate_id'  => 'required',
            'sla_id'        => 'required',
            'data'     => 'required'
        ]);

        $data = $request->get('data');
        
 
        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                     'message'=>'The given data was invalid.',
                                     'errors'=> $validator->errors()], 200);
        }
 
        // store variable
        $candidate_id  = $request->get('candidate_id');
        $sla_id = 13;
        $services = [];
        $form_inputs=[];

        $check_sla = DB::table('jobs as j')
        ->select('j.*')
        ->where(['j.sla_id'=>$sla_id])
        ->first();
 
        // check job exit or not
        if($check_sla ===null)
        {  
           return response()->json(['status' => 'error','error_message'=>'It seems like link is not exist!, please contact to your admin'], 200);
        }
         
         //response data
         $response =  ['status'=>'success',
                              'message'=>'',
                              'remarks'=>'',
                              'data'=>  [$data
                                 
                                        ]
                             ]; 
 
         return response()->json($response, 200);
        
     }
    
    // get service name 
    public function get_service_name($service_id)
    {  
        $data = DB::table('services as s')
        ->select('s.name')            
        ->where(['s.id'=>$service_id])
        ->first();
        $res = "";
        if($data !=null){
            $res = $data->name;
        }
        return $res;
    } 

    // get service name 
    public function get_service_form_inputs($service_id)
    { 
        $form_inputs=[];
        //form inputs item
        $form_input_datas = DB::table('service_form_inputs as sfi')
        ->select('sfi.*')            
        ->where(['sfi.service_id'=>$service_id])
        ->get();

        foreach($form_input_datas as $inputItem){
                        
            $input_type = $this->get_form_input_type($inputItem->form_input_type_id);
            
            $requried = FALSE;
            if($requried == TRUE){
                $requried = TRUE;
            }

            $form_input_type_id = ""; 
            $input_type_id =  $inputItem->form_input_type_id;
            $input_label_id =  $inputItem->id;

            $form_inputs[] = ['inputLable'=>$inputItem->label_name, 'inputLabelId'=>$input_label_id,'inputType'=>$input_type, 'inputTypeId'=>$input_type_id, 'required'=>$requried];
        }

        return $form_inputs;

    }

    // get get form input type name 
    public function get_form_input_type($service_id)
    {  
        $data = DB::table('form_input_masters as fm')
        ->select('fm.name')            
        ->where(['fm.id'=>$service_id])
        ->first();
        $res = "";
        if($data !=null){
            $res = $data->name;
        }
        return $res;
    } 
    // candidate addrerss verification form
    public function address_verification(Request $request)
    {  
        $candidate_id = "";

        $successResponse = ['status'=>'success',
                            'data'=>[
                                    'form_name'=>'Address Verification',
                                    'form_inputs'  =>[
                                                ['inputType'=>'text','inputLabel'=>'First Name','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Last Name','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Phone','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Email','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Street address','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Apartment/House or Building','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Pincode','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'City/Town/District','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'State','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Country','required'=>true],
                                                ['inputType'=>'radioGroup',
                                                'type_values'=>[
                                                    ['name'=>'Permanent'],['name'=>'Current']
                                                ],
                                                'inputLabel'=>'Address Type','required'=>true],
                                                ['inputType'=>'text','inputLabel'=>'Ownership','required'=>true],
                                                ['inputType'=>'photo','inputLabel'=>'Profile Image','required'=>true],
                                                ['inputType'=>'photo','inputLabel'=>'Address Proof','required'=>true],
                                                
                                               ],
                                
                                    ],                            
                        ]; 
      
        return response()->json($successResponse, 200);     
       
    }


}
