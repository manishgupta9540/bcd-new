<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CustomerSla;
use App\Models\Admin\Task;
use App\Models\Admin\TaskAssignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helper;
use App\Exports\MISDataExport;
use App\Exports\OPSExport;
use App\Exports\SlaExport;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
//  use Illuminate\Testing\Constraints\SoftDeletedInDatabase
use App\Traits\S3ConfigTrait;
use App\Models\Admin\CandidateReinitiate;
use App\Exports\GuestPayReport;
use App\Models\Admin\ReportItem;
use App\Models\Admin\JafFormData;

class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        ini_set('max_execution_time', '0');
    }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jaf_form()
    {

        return view('admin.jaf.form');
    }

    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $business_id = Auth::user()->business_id;

        $report_config = DB::table('report_config')->where(['business_id'=>$business_id])->first();

        $candidate_config = DB::table('candidate_config')->where(['business_id'=>$business_id])->first();

        $company= DB::table('users')->select('company_logo','company_logo_file_platform')->where(['business_id'=>$business_id])->first();

        return view('admin.settings.general', compact('company','report_config','candidate_config'));
    }

    //updateReportConfig
    public function updateReportConfig(Request $request)
    {
        //  dd($request);
         $i=0;
         $business_id = Auth::user()->business_id;
         $company_name   = NULL;
         // Form validation
         $this->validate($request, [
         'company_name'      => 'required',
         'company_address'   => 'required'
          ]
         );
 
         //update report config
         $b_data = 
         [
             'company_name'          => $request->input('company_name'),
             'company_address'       => $request->input('company_address'),
             'updated_by'            => Auth::user()->id,
             'updated_at'            => date('Y-m-d H:i:s')
         ];
         
         $count = DB::table('report_config')->where(['business_id'=>$business_id])->count();
         if($count > 0 ){
            DB::table('report_config')->where(['business_id'=>$business_id])->update($b_data);
         }else{

            $b_data = 
            [
            'business_id'            =>$business_id,
            'company_name'          => $request->input('company_name'),
            'company_address'       => $request->input('company_address'),
            'updated_by'            => Auth::user()->id,
            'updated_at'            => date('Y-m-d H:i:s')
            ];

            DB::table('report_config')->insert($b_data);

         }
         
         return redirect()
             ->route('/settings/general')
             ->with('success', 'Data updated successfully.');
 
    }

     //updateReportConfig
     public function updateCandidateConfig(Request $request)
     {
         //  dd($request);
          $i=0;
          $business_id = Auth::user()->business_id;
          $company_name   = NULL;
          // Form validation
          $this->validate($request, [
          'company_short_name'  => 'required',
          'starting_number'     => 'required'
           ]
          );
  
          //update report config
          $b_data = 
          [
              'customer_prefix'       => trim($request->input('company_short_name')),
              'starting_number'       => trim($request->input('starting_number')),
              'updated_by'            => Auth::user()->id,
              'updated_at'            => date('Y-m-d H:i:s')
          ];
          
          $count = DB::table('candidate_config')->where(['business_id'=>$business_id])->count();
          if($count > 0 ){
             DB::table('candidate_config')->where(['business_id'=>$business_id])->update($b_data);
          }else{
 
             $b_data = 
             [
             'business_id'           =>$business_id,
             'customer_prefix'       => trim($request->input('company_short_name')),
             'starting_number'       => trim($request->input('starting_number')),
             'updated_by'            => Auth::user()->id,
             'updated_at'            => date('Y-m-d H:i:s')
             ];
 
             DB::table('candidate_config')->insert($b_data);
 
          }
          
          return redirect()
              ->route('/settings/general')
              ->with('success', 'Data updated successfully.');
  
     }



     //updateMailConfig
     public function updateMailConfig(Request $request)
     {
         //  dd($request);
          $i=0;
          $business_id = Auth::user()->business_id;
         
          // Form validation
          $this->validate($request, [
          'email'  => 'required',
          'password'     => 'required'
           ]
          );
  
          //update mail config
          $b_data = 
          [
              'email'       => $request->email,
              'password'       => $request->password,
              'business_id'     => $business_id,
              'updated_by'            => Auth::user()->id,
              'updated_at'            => date('Y-m-d H:i:s')
          ];
          
          $count = DB::table('email_smtps')->where(['business_id'=>$business_id])->first();
          if($count){
             DB::table('email_smtps')->where(['business_id'=>$business_id])->update($b_data);
          }else{
 
             $b_data = 
             [
                'email'       => $request->email,
                'password'       => $request->password,
                'business_id'     => $business_id,
                'updated_by'            => Auth::user()->id,
                'updated_at'            => date('Y-m-d H:i:s')
             ];
             DB::table('email_smtps')->insert($b_data);
 
          }
          
          return redirect()
              ->route('/settings/general')
              ->with('success', 'Data updated successfully.');
  
     }


    //JAF Form data save
    public function jafSave(Request $request)
    {
      // dd($request);
      $case_id      = base64_decode($request->input('case_id'));
      $candidate_id = $request->input('candidate_id');
      $business_id  = $request->input('business_id'  );

      $job_sla_items = DB::table('job_sla_items')->where(['job_id'=>$case_id])->get();

      $input_data = [];
    
      foreach($job_sla_items as $service){
        
        // service-input-label-0-1-1
        // echo $request->input('service-input-label-0-1-1');
        // die('ok');
        $input_items = DB::table('service_form_inputs as sfi')
                    ->select('sfi.*')            
                    ->where(['sfi.service_id'=>$service->sla_item_id])
                    ->get();
        $numbers_of_items = $service->number_of_verifications;
       
        for($j=1; $j<=$numbers_of_items; $j++){
          $input_data = [];
          $i=0;
          foreach($input_items as $input){
            $input_data[] = [
                              $request->input('service-input-label-'.$i.'-'.$service->sla_item_id.'-'.$j)=>$request->input('service-input-value-'.$i.'-'.$service->sla_item_id.'-'.$j),
                              'is_report_output'=>$input->is_report_output 
                            ];
              $i++;
          }
        
          $jaf_data = json_encode($input_data);
          
          //insuff
          $is_insufficiency = 0;
          if($request->has('insufficiency-'.$i.'-'.$service->sla_item_id)){
            $is_insufficiency = 1;
          }
          $insufficiency_notes = $request->input('insufficiency-notes-'.$i.'-'.$service->sla_item_id);

          $address_type = $request->input('address-type-'.$service->sla_item_id.'-'.$j);

          $jaf_form_data = ['business_id' => $business_id,
                            'job_id'      => $case_id,
                            'job_item_id' => $service->sla_item_id,
                            'service_id'  => $service->service_id,
                            'candidate_id'=> $candidate_id,
                            'form_data'   => $jaf_data,
                            'check_item_number'=>$j,
                            'sla_id'      =>$service->sla_id,
                            'form_data_all'=> json_encode($request->all()),
                            'is_insufficiency'=>$is_insufficiency,
                            'insufficiency_notes'=>$insufficiency_notes,
                            'address_type'=>$address_type,
                            'created_by'   => Auth::user()->id,
                            'created_at'   => date('Y-m-d H:i:s')];

          DB::table('jaf_form_data')->insert($jaf_form_data);

          DB::table('job_items')->where(['job_id'=>$case_id])->update(['jaf_status'=>'filled']);
          
        }
        // 
      }

      return redirect()
            ->route('/candidates/jaf-info',['case_id'=> base64_encode($candidate_id)])
            ->with('success', 'Candidate JAF submitted.');

    }


    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jaf()
    {
        $company= DB::table('users')->select('company_logo')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('admin.settings.jaf', compact('company'));
    }

    /**
     * Show the sla.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*','u.first_name','u.last_name','ub.company_name')
                ->join('users as u','u.id','=','sla.business_id')
                ->join('user_businesses as ub','ub.business_id','=','sla.business_id')
                ->where(['u.parent_id'=>Auth::user()->business_id]);
                    if($request->get('ref')){
                        $sla->where('sla.display_id',$request->get('ref'));
                    }
                    if(is_numeric($request->get('customer_id'))){
                        $sla->where('sla.business_id',$request->get('customer_id'));
                    }
                    if($request->get('sla_name')){
                        $sla->where('sla.id',$request->get('sla_name'));
                    }
                    if($request->get('check')){
                        $sla->join('customer_sla_items as csi','csi.sla_id', '=','sla.id')->whereIn('csi.service_id',explode(',',$request->get('check')));
                    }
                    $sla=$sla->orderBy('sla.id','desc')->paginate(10);
        
        $customers = DB::table('users as u')
                ->select('u.id','u.first_name','u.last_name','b.company_name')
                ->join('user_businesses as b','b.business_id','=','u.id')
                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'client'])
                ->get();
        
        $services = DB::table('services')
                ->select('name','id')
                ->where('business_id',NULL)
                ->whereNotIn('type_name',['e_court'])
                ->orwhere('business_id',$business_id)
                ->where(['status'=>1])
                ->orderBy('sort_number','asc')
                ->get();        

        if($request->ajax())
            return view('admin.accounts.sla_ajax', compact('services','customers','sla'));
        else
            return view('admin.accounts.sla', compact('services','customers','sla'));
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
                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'client'])
                ->get();

        $services = DB::table('services as s')
                    ->select('s.*')
                    ->join('service_form_inputs as si','s.id','=','si.service_id')
                    ->where('s.business_id',NULL)
                    ->whereNotIn('s.type_name',['gstin'])
                    ->orwhere('s.business_id',$business_id)
                    ->where('s.status',1)
                    ->orderBy('s.sort_number','asc')
                    ->groupBy('si.service_id')
                    ->get();

        return view('admin.accounts.sla-create', compact('customers','services'));
    }

    /**
     * store the data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla_save(Request $request)
    {
        $max_service_tat=0;
        $rules= 
        [
            'customer'   => 'required', 
            'name'       => 'required', 
            'tat'        => 'required|integer|min:1|lt:client_tat', 
            'client_tat' => 'required|integer|min:1',
            'days_type'  => 'required|in:working,calender',
            'tat_type'  => 'required|in:case,check',
            'price_type'  => 'required|in:package,check',
            'incentive' => 'required_if:tat_type,case|numeric|min:0',
            'penalty'   => 'required_if:tat_type,case|numeric|min:0',
            'price'   => 'required_if:price_type,package|numeric|min:0',
            'services'   => 'required|array|min:1',
        ];
        $custom = [
            'incentive.required_if' => 'The incentive field is required',
            'penalty.required_if' => 'The penalty field is required',
            'price.required_if' => 'The price field is required',
            'tat.lt' => 'The tat must be less than client tat'
        ];
        $validator = Validator::make($request->all(), $rules, $custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $business_id = Auth::user()->business_id;

        DB::beginTransaction();
        try{

            $check_name = DB::table('customer_sla')->where(['business_id'=>$request->input('customer'), 'title'=>$request->input('name')])->count();

            if($check_name > 0 ){

                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['name'=>'SLA name is already exist!']
                ]);

            }

            if($request->input('tat') > $request->input('client_tat'))
            {
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['client_tat'=>'Internal TAT should not greater than Client TAT']
                ]);

            }
            //validation for no_of_verification and check_tat
            if( count($request->input('services') ) > 0 ){
                $arr = [];
                foreach($request->input('services') as $service){
                    $services=DB::table('services')->where('id',$service)->first();
                    $rules=[
                        'service_unit-'.$service    => 'required|integer|min:1',
                        'tat-'.$service => 'required_if:tat_type,check|integer|min:1',
                        'incentive-'.$service => 'required_if:tat_type,check|integer|lte:tat-'.$service,
                        'penalty-'.$service => 'required_if:tat_type,check|integer|gte:tat-'.$service,
                        'price-'.$service => 'required_if:price_type,check|numeric|min:0'
                    ];
                    $customMessages=[
                        'service_unit-'.$service.'.required' => 'No of Verification is required',
                        'service_unit-'.$service.'.integer' => 'No of Verification should be numeric',
                        'service_unit-'.$service.'.min' => 'No of Verification should be atleast 1',
                        // 'service_unit-'.$service.'.max' => 'No of Verification should be Maximum 5',
                        'tat-'.$service.'.required_if' => 'No of TAT is required',
                        'tat-'.$service.'.integer' => 'No of TAT should be numeric',
                        'tat-'.$service.'.min' => 'No of TAT should be atleast 1',
                        'incentive-'.$service.'.required_if' => 'No of incentive TAT is required',
                        'incentive-'.$service.'.integer' => 'No of incentive TAT should be numeric',
                        'incentive-'.$service.'.lte' => 'No of Incentive TAT should be less than or equal to Service TAT',
                        'penalty-'.$service.'.required_if' => 'No of Penalty TAT is required',
                        'penalty-'.$service.'.integer' => 'No of Penalty TAT should be numeric',
                        'penalty-'.$service.'.gte' => 'No of Penalty TAT should be greater than or equal to Service TAT',
                        'price-'.$service.'.required_if' => 'Check Price is required',
                        'price-'.$service.'.numeric' => 'Check Price should be numeric',
                        'price-'.$service.'.min' => 'Check Price should be minimum value 0',
                    ];
                        $validator = Validator::make($request->all(), $rules,$customMessages);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }

                    // $max_service_tat =  $max_service_tat + $request->input('tat-'.$service);

                    $arr[]= intval($request->input('tat-'.$service));
                }

                $max_service_tat = max($arr);
            }

            //check if Internal or Client TAT is less than Overall Service TATs
            if($max_service_tat > $request->tat)
            {
                if($request->tat==$request->client_tat || $max_service_tat > $request->client_tat)
                {
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['tat'=>'Internal TAT should be greater than Max Service TATs','client_tat'=>'Client TAT should be greater than Max Service TATs']
                    ]);
                }
                else
                {
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['tat'=>'Internal TAT should be greater than Max Service TATs']
                    ]);
                }
            }
            else if($max_service_tat > $request->client_tat)
            {
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['client_tat'=>'Client TAT should be greater than Max Service TATs']
                ]);
            }

            // dd(1);
                $data = [
                            'business_id'=> $request->input('customer'),
                            'parent_id'  => Auth::user()->business_id,
                            'title'      => $request->input('name'),
                            'tat'        => $request->input('tat'),
                            'client_tat' => $request->input('client_tat'),
                            'days_type'  => $request->input('days_type'),
                            'tat_type'  => $request->input('tat_type'),
                            'price_type'  => $request->input('price_type'),
                            'created_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                $sla_id = CustomerSla::create($data);
                $sla_id  =$sla_id->id;

                $customer_sla = DB::table('customer_sla as cs')
                            ->select('cs.*','ub.company_name','ub.company_short_name')
                            ->join('user_businesses as ub','cs.business_id','=','ub.business_id')
                            ->where('cs.id',$sla_id)
                            ->first();

                $s_id = str_pad($sla_id, 10, "0", STR_PAD_LEFT);
                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($customer_sla->company_name,0,4)))).'-'.trim(strtoupper(substr($customer_sla->title,0,4))).'-'.$s_id;

                DB::table('customer_sla')->where('id',$customer_sla->id)->update([
                    'display_id' => $display_id 
                ]);

                if(stripos($request->tat_type,'case')!==false)
                {
                    $update=  CustomerSla::find($sla_id);
                    $update->update([
                        'incentive' => $request->incentive,
                        'penalty' => $request->penalty,
                    ]);
                }

                if(stripos($request->price_type,'package')!==false)
                {
                    DB::table('customer_sla')->where(['id'=>$sla_id])->update([
                        'package_price' => $request->price,
                    ]);
                }

            $i = 0;
            $number_of_verifications =1;

            $no_of_tat=1;

            $incentive_tat=1;

            $penalty_tat = 1;

            $price = 0;
        
            if( count($request->input('services') ) > 0 ){
                foreach($request->input('services') as $service){

                $number_of_verifications = $request->input('service_unit-'.$service);
                $notes = $request->input('notes-'.$service);

                $no_of_tat = $request->input('tat-'.$service);

                $incentive_tat = $request->input('incentive-'.$service);

                $penalty_tat = $request->input('penalty-'.$service);

                $price = $request->input('price-'.$service);

                $service_d=DB::table('services')->where('id',$service)->first();
                    
                    $data = [
                        'business_id'   =>$request->input('customer'),
                        'sla_id'        =>$sla_id,
                        'number_of_verifications'=> $service_d->verification_type=='Manual' || $service_d->verification_type=='manual'?$number_of_verifications:1,
                        'service_id'    =>$service,
                        'notes'         =>$notes,
                        'tat'           => $no_of_tat,
                        'incentive_tat' => $incentive_tat,
                        'penalty_tat' => $penalty_tat,
                        'price' => $price,
                        'created_at'    =>date('Y-m-d H:i:s')
                    ];

                    DB::table('customer_sla_items')->insert($data);
                    $i++;
                }
            }

            DB::commit();
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    
    }

  public function sla_edit($id)
  {
        $id=base64_decode($id);
        $business_id = Auth::user()->business_id;

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*','u.company_name')
                ->join('user_businesses as u','u.business_id','=','sla.business_id')
                ->where(['sla.id'=>$id])
                ->first();
        
        $sla_items = DB::table('customer_sla_items as sla')
        ->select('s.id','s.name','s.verification_type','sla.number_of_verifications','sla.notes','sla.id as sla_item_id','sla.tat as check_tat','sla.incentive_tat','sla.penalty_tat','sla.price')
        ->join('services as s','s.id','=','sla.service_id')
        ->where(['sla.sla_id'=>$id])
        ->get();

        $selected_services_id = [];
        foreach($sla_items as $item){
            $selected_services_id[] = $item->id;
        }

        $total_checks = DB::table('customer_sla_items')
                            ->select('number_of_verifications')
                            ->where(['sla_id'=>$id])
                            ->sum('number_of_verifications');

        $total_check_price = DB::table('customer_sla_items')
                                ->select('price')
                                ->where(['sla_id'=>$id])
                                ->sum('price');

        // $services= DB::table('services as s')
        //     ->select('s.*')
        //     ->join('service_form_inputs as si','s.id','=','si.service_id')
        //     ->where('s.status',1)
        //     ->where('s.business_id',NULL)
        //     ->whereNotIn('s.type_name',['gstin'])
        //     ->orwhere('s.business_id',$business_id)
        //     ->orderBy('s.sort_number','asc')
        //     ->groupBy('si.service_id')
        //     ->get();

        $services = DB::table('services as s')
            ->select('s.*')
            ->join('service_form_inputs as si','s.id','=','si.service_id')
            ->where('s.business_id',NULL)
            ->whereNotIn('s.type_name',['gstin'])
            ->orwhere('s.business_id',$business_id)
            ->where('s.status',1)
            ->orderBy('s.sort_number','asc')
            ->groupBy('si.service_id')
            ->get();       

        return view('admin.accounts.sla-edit', compact('services','sla','sla_items','selected_services_id','total_checks','total_check_price'));
  }

    public function sla_update(Request $request)
    {
        // dd($request);
        // $this->validate($request, 
        // [
        //     'name'      => 'required', 
        //     'services'   => 'required|array|min:1',
        //     'tat'        => 'required|numeric|lte:client_tat', 
        //     'client_tat' => 'required|numeric|gte:tat', 
        // ]);
        $max_service_tat=0;
        $rules= 
        [
            // 'customer'   => 'required', 
            'name'       => 'required', 
            'tat'        => 'required|integer|min:1|lt:client_tat', 
            'client_tat' => 'required|integer|min:1', 
            'tat_type'  => 'required|in:case,check',
            'price_type'  => 'required|in:package,check',
            'incentive' => 'required_if:tat_type,case|numeric|min:0',
            'penalty'   => 'required_if:tat_type,case|numeric|min:0',
            'price'   => 'required_if:price_type,package|numeric|min:0',
            'services'   => 'required|array|min:1',
        ];
        $custom = [
            'incentive.required_if' => 'The incentive field is required',
            'penalty.required_if' => 'The penalty field is required',
            'price.required_if' => 'The price field is required',
            'tat.lt' => 'The tat must be less than client tat'
        ];
        $validator = Validator::make($request->all(), $rules, $custom);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try{

            $sla_id = $request->input('sla_id');

            $check_name = DB::table('customer_sla')
            ->where(['business_id'=>$request->input('customer'), 'title'=>$request->input('name')])
            ->whereNotIn('id',[$sla_id])
            ->count();

            if($check_name > 0 ){

                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['name'=>'SLA name is already exist!']
                ]);

            }

            if($request->input('tat') > $request->input('client_tat'))
            {
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['client_tat'=>'Internal TAT should not greater than Client TAT']
                ]);

            }

            //validation for no_of_verification and check_tat
            if( count($request->input('services') ) > 0 ){
                $arr=[];
                foreach($request->input('services') as $service){

                    $rules=[
                        'service_unit-'.$service    => 'required|integer|min:1',
                        'tat-'.$service => 'required_if:tat_type,check|integer|min:1',
                        'incentive-'.$service => 'required_if:tat_type,check|integer|lte:tat-'.$service,
                        'penalty-'.$service => 'required_if:tat_type,check|integer|gte:tat-'.$service,
                        'price-'.$service => 'required_if:price_type,check|numeric|min:0'
                    ];
                    $customMessages=[
                        'service_unit-'.$service.'.required' => 'No of Verification is required',
                        'service_unit-'.$service.'.integer' => 'No of Verification should be numeric',
                        'service_unit-'.$service.'.min' => 'No of Verification should be atleast 1',
                        // 'service_unit-'.$service.'.max' => 'No of Verification should be Maximum 3',
                        'tat-'.$service.'.required_if' => 'No of TAT is required',
                        'tat-'.$service.'.integer' => 'No of TAT should be numeric',
                        'tat-'.$service.'.min' => 'No of TAT should be atleast 1',
                        'incentive-'.$service.'.required_if' => 'No of incentive TAT is required',
                        'incentive-'.$service.'.integer' => 'No of incentive TAT should be numeric',
                        'incentive-'.$service.'.lte' => 'No of Incentive TAT should be less than or equal to Service TAT',
                        'penalty-'.$service.'.required_if' => 'No of penalty TAT is required',
                        'penalty-'.$service.'.integer' => 'No of penalty TAT should be numeric',
                        'penalty-'.$service.'.gte' => 'No of penalty TAT should be greater than or equal to Service TAT',
                        'price-'.$service.'.required_if' => 'Check Price is required',
                        'price-'.$service.'.numeric' => 'Check Price should be numeric',
                        'price-'.$service.'.min' => 'Check Price should be minimum value 0',
                    ];
                        $validator = Validator::make($request->all(), $rules,$customMessages);
                        
                        if ($validator->fails()){
                            return response()->json([
                                'success' => false,
                                'errors' => $validator->errors()
                            ]);
                        }

                    // $max_service_tat =  $max_service_tat + $request->input('tat-'.$service);
                    $arr[]= intval($request->input('tat-'.$service));

                }

                $max_service_tat = max($arr);
            }

            //check if Internal or Client TAT is less than Overall Service TATs
            if($max_service_tat > $request->tat)
            {
                if($request->tat==$request->client_tat || $max_service_tat > $request->client_tat)
                {
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['tat'=>'Internal TAT should be greater than Max Service TATs','client_tat'=>'Client TAT should be greater than Max Service TATs']
                    ]);
                }
                else
                {
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['tat'=>'Internal TAT should be greater than Max Service TATs']
                    ]);
                }
            }
            else if($max_service_tat > $request->client_tat)
            {
                return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['client_tat'=>'Client TAT should be greater than Max Service TATs']
                ]);
            }

            $business_id = Auth::user()->business_id;
            $data = [
                        'tat'        =>$request->input('tat'),
                        'client_tat' =>$request->input('client_tat'),
                        'title'      =>$request->input('name'),
                        'price_type'  => $request->input('price_type'),
                        'updated_by' => Auth::user()->id,
                        'updated_at' =>date('Y-m-d H:i:s')
                    ];
            
            $sla_id = $request->input('sla_id');
            $update=  CustomerSla::find($sla_id);
            $update->update($data);

            $customer_sla = DB::table('customer_sla as cs')
                            ->select('cs.*','ub.company_name','ub.company_short_name')
                            ->join('user_businesses as ub','cs.business_id','=','ub.business_id')
                            ->where('cs.id',$sla_id)
                            ->first();
                            
            if($customer_sla->display_id==null)
            {
                $s_id = str_pad($sla_id, 10, "0", STR_PAD_LEFT);
                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($customer_sla->company_name,0,4)))).'-'.trim(strtoupper(substr($customer_sla->title,0,4))).'-'.$s_id;

                DB::table('customer_sla')->where('id',$customer_sla->id)->update([
                    'display_id' => $display_id 
                ]);
            }
            // DB::table('customer_sla')->where(['id'=>$request->input('sla_id')])->update($data);
            
            if(stripos($request->tat_type,'case')!==false)
            {
                $update=  CustomerSla::find($sla_id);
                $update->update(
                    [
                        'incentive' => $request->incentive,
                        'penalty' => $request->penalty,
                        'updated_at' =>date('Y-m-d H:i:s')
                    ]
                );
            }

            if(stripos($request->price_type,'package')!==false)
            {
                DB::table('customer_sla')->where(['id'=>$request->input('sla_id')])->update([
                    'package_price' => $request->price,
                ]);
            }
                    
            //update service items
            DB::table('customer_sla_items')->where(['sla_id'=>$request->input('sla_id')])->delete();
            
            $i = 0;
            $number_of_verifications =1;
            $no_of_tat=1;
            $incentive_tat=1;
            $penalty_tat=1;
            $price=0;
            if( count($request->input('services') ) > 0 ){
                foreach($request->input('services') as $service){
    
                $number_of_verifications = $request->input('service_unit-'.$service);
                $notes = $request->input('notes-'.$service);
                $no_of_tat = $request->input('tat-'.$service);
                $incentive_tat = $request->input('incentive-'.$service);
                $penalty_tat = $request->input('penalty-'.$service);
                $price = $request->input('price-'.$service);

                $service_d=DB::table('services')->where('id',$service)->first();
                
                        $data = [
                            'business_id'   =>$request->input('customer'),
                            'sla_id'        =>$sla_id,
                            'number_of_verifications'=> $service_d->verification_type=='Manual' || $service_d->verification_type=='manual'?$number_of_verifications:1,
                            'service_id'    =>$service,
                            'notes'         =>$notes,
                            'tat'           => $no_of_tat,
                            'incentive_tat' => $incentive_tat,
                            'penalty_tat' => $penalty_tat,
                            'price' => $price,
                            'created_at'    =>date('Y-m-d H:i:s')
                        ];
                        DB::table('customer_sla_items')->insert($data);
                
                    $i++;
                }
            }



            // return redirect('/sla')
            //     ->with('success', 'SLA updated successfully.');
            DB::commit();
            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    
        
    }

    public function sla_view($id)
    {

        $id=base64_decode($id);
        $business_id = Auth::user()->business_id;

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*','u.company_name')
                ->join('user_businesses as u','u.business_id','=','sla.business_id')
                ->where(['sla.id'=>$id])
                ->first();
        
        $sla_items = DB::table('customer_sla_items as sla')
        ->select('s.id','s.name','sla.number_of_verifications','sla.notes','sla.id as sla_item_id','sla.tat as check_tat','sla.incentive_tat','sla.penalty_tat','sla.price')
        ->join('services as s','s.id','=','sla.service_id')
        ->where(['sla.sla_id'=>$id])
        ->get();

        $selected_services_id = [];
        foreach($sla_items as $item){
            $selected_services_id[] = $item->id;
        }

        // dd($selected_services_id);

        $services= DB::table('services as s')
            ->select('s.*')
            ->join('service_form_inputs as si','s.id','=','si.service_id')
            ->where('s.business_id',NULL)
            ->whereNotIn('s.type_name',['gstin'])
            ->orwhere('s.business_id',$business_id)
            ->orderBy('s.sort_number','asc')
            ->groupBy('si.service_id')
            ->get();

            $total_checks = DB::table('customer_sla_items')
            ->select('number_of_verifications')
            ->where(['sla_id'=>$id])
            ->sum('number_of_verifications');

            $total_check_price = DB::table('customer_sla_items')
                                    ->select('price')
                                    ->where(['sla_id'=>$id])
                                    ->sum('price');
    
        return view('admin.accounts.sla_view',compact('services','sla','sla_items','selected_services_id','total_checks','total_check_price'));
         

    }

    // upload  file.
    public function uploadCompanyLogo(Request $request)
    {        
        // dd($request);
        // $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw","doc","pdf","docx","jpg","png","jpeg","PNG","JPG","JPEG","csv","gif","txt",".apk");

        $extensions = array("jpg","png","jpeg","PNG","JPG","JPEG","gif");

        if( $request->hasFile('file') ) {
        
        $result = array($request->file('file')->getClientOriginalExtension());

        if(in_array($result[0],$extensions))
        {      
            $file_platform = 'web';

            $attachment_file  = $request->file('file');
            $orgi_file_name   = $attachment_file->getClientOriginalName();
            
            $fileName         = pathinfo($orgi_file_name,PATHINFO_FILENAME);

            $filename         = time().'-'.$fileName.'.'.$attachment_file->getClientOriginalExtension();
            $dir              = public_path('/uploads/company-logo/');  
            
            $s3_config = S3ConfigTrait::s3Config();

            if($s3_config!=NULL)
            {
                $file_platform = 's3';

                $path = 'uploads/company-logo/';

                if(!Storage::disk('s3')->exists($path))
                {
                    Storage::disk('s3')->makeDirectory($path,0777, true, true);
                }

                Storage::disk('s3')->put($path.$filename, file_get_contents($attachment_file));
            }
            else
            {
                $request->file('file')->move($dir, $filename);
            }    
            
                
            $asset_id = NULL;
            $is_temp = 1;

            DB::table('users')          
            ->where(['business_id'=>Auth::user()->business_id])  
            ->update([                    
                            'company_logo'  => $filename,
                            'company_logo_file_platform' => $file_platform,
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
                        if(stripos($file_platform,'s3')!==false)
                        {
                            $filePath = 'uploads/company-logo/';

                            $disk = Storage::disk('s3');

                            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                'Key'                        => $filePath.$filename,
                                //'ResponseContentDisposition' => 'attachment;'//for download
                            ]);

                            $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                            $type = (string)$req->getUri();  
                        }
                        else
                        {         
                            $type = url('/').'/uploads/company-logo/'.$filename;
                        }
                    }
                    
                }           

                return response()->json([
                    'fail' => false,
                    'filename' => $filename,
                    'filePrev'=>$type
            ]); 

            }else{
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

    /**
     *
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateCandidateID()
    {
        //
        // echo Auth::user()->business_id;
        // die;
        $users = DB::table('job_sla_items')
                ->where(['candidate_id'=>NULL])
                ->get();
        
        // dd($users);

        foreach($users as $item){

            $data = DB::table('job_items')
                ->where(['id'=>$item->job_item_id])
                ->first();

            DB::table('job_sla_items')
            ->where(['job_item_id'=>$item->job_item_id])
            ->update(['candidate_id'=>$data->candidate_id]);

        }

    }
    

    public function reference()
    {
        $users = DB::table('candidate_reinitiates')->where(['user_type'=>'candidate','display_id'=>NULL])->get();
        foreach ($users as $key => $user) {
            // dd($user->business_id);
            $display_id = "";
            //check customer config
           $candidate_config = DB::table('candidate_config')
           ->where(['client_id'=>$user->business_id,'business_id'=>$user->parent_id])
           ->first();
           
           //check client 
           $client_config = DB::table('user_businesses')
           ->where(['business_id'=>$user->business_id])
           ->first(); 
        //    dd($client_config);
           $latest_user = DB::table('candidate_reinitiates')
           ->select('display_id')
           ->where(['user_type'=>'candidate','business_id'=>$user->business_id,'display_id'=>NULL])
       
           ->first();
           
           $starting_number = $user->id;
        //    dd($starting_number);
           if($candidate_config !=null){
             if($latest_user != null){
               if($latest_user->display_id !=null){
                 $id_arr = explode('-',$latest_user->display_id);
                 $starting_number = $id_arr[2]+1;  
               }
             }
             $starting_number = str_pad($starting_number, 10, "0", STR_PAD_LEFT);
            //  dd($starting_number);
             $display_id = $candidate_config->customer_prefix.'-'.$candidate_config->client_prefix.'-'.$starting_number;
           }else{
             $customer_company = DB::table('user_businesses')
               ->select('company_name')
               ->where(['business_id'=>$user->parent_id])
               ->first();
            //    dd($customer_company);
              $client_company = DB::table('user_businesses')
               ->select('company_name')
               ->where(['business_id'=>$user->business_id])
               ->first();
               
               $u_id = str_pad($user->id, 10, "0", STR_PAD_LEFT);
            //    dd($u_id);
              $display_id = trim(strtoupper(substr($customer_company->company_name,0,4))).'-'.trim(strtoupper(substr($client_company->company_name,0,4))).'-'.$u_id;
           }
        //    dd($display_id);
           DB::table('candidate_reinitiates')->where(['id'=>$user->id])->update(['display_id'=>$display_id]);
           
        }
        // dd($users);
    }


    public function verificationStatus()
    {
        
        $reports = DB::table('reports')->where('status','completed')->get();


        foreach ($reports as $key => $report) {
            
            $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$report->candidate_id])->whereNotNull('form_data')->get();
            foreach ($jaf_form_data as $key => $jad) {
                // dd($jad);
                DB::table('jaf_form_data')->where(['candidate_id'=>$jad->candidate_id,'service_id'=>$jad->service_id])->update(['verification_status'=>'success']);
                
                // dd($jad);
            }
            
            // dd($jaf_form_data);
        }
        // dd($reports);
    }

    //jaf send to update query
    public function jafSend()
    {
        //   
        $job_sla_items = DB::table('job_sla_items')->where(['jaf_send_to'=>NULL])->get();

        foreach ($job_sla_items as $key => $jsi) {
            DB::table('job_sla_items')->where('jaf_send_to',NULL)->update(['jaf_send_to'=>'customer']);
        }

   
        //    dd($job_sla_items);
    }
    public function jafCandidateId()
    {
        $job_items = DB::table('job_items')->get();
        foreach ($job_items as $job_item) {
            $job_sla_items = DB::table('job_sla_items')->where(['job_item_id'=>$job_item->id])->whereNull('candidate_id')->get();
            //    dd($job_sla_items);
            foreach ($job_sla_items as $job_sla_item) {
             // dd($job_sla_item);
             $job_sla_items = DB::table('job_sla_items')->where(['job_item_id'=>$job_item->id])->whereNull('candidate_id')->update(['candidate_id'=>$job_item->candidate_id]);

            }
            
        }
    }
    
    //creating blank JAF form in  
    public function blankJaf()
    {
        // dd($job_items);
        $job_sla_items = DB::table('job_sla_items')->get();
        // dd($job_sla_items);
        // $input_data = [];
        $numbers = [];

        foreach ($job_sla_items as $job_sla_item) {
            
            $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$job_sla_item->candidate_id,'service_id'=>$job_sla_item->service_id])->count();

            if($jaf_form_data == 0){

                $numbers[] = $job_sla_item->candidate_id;

                    $input_items = DB::table('service_form_inputs as sfi')
                    ->select('sfi.*')            
                    ->where(['sfi.service_id'=>$job_sla_item->sla_item_id])
                    ->get();
                    
                    $numbers_of_items = $job_sla_item->number_of_verifications;
                    
                    for($j=1; $j<=$numbers_of_items; $j++){
                    
                    $jaf_form_data = [
                                        'business_id' => $job_sla_item->business_id,
                                        'job_id'      => $job_sla_item->job_id,
                                        'job_item_id' => $job_sla_item->job_item_id,
                                        'service_id'  => $job_sla_item->service_id,
                                        'candidate_id' => $job_sla_item->candidate_id,
                                        'check_item_number'=>$j,
                                        'sla_id'      =>$job_sla_item->sla_id,
                                        'created_by'   => Auth::user()->id,
                                        'created_at'   => date('Y-m-d H:i:s')];

                    $jaf_data= DB::table('jaf_form_data')->insert($jaf_form_data);
                    
                    }
            }

        }
        echo "<pre>";
        echo "Total count= ".count($numbers);
        
        // $data_uni = array_unique($numbers);

        print_r($numbers);

       
    }

    public function jafFilled()
    {
        $job_items = DB::table('job_items')->where(['jaf_status'=>'filled'])->get();

        foreach ($job_items as $job_item) {

            $user = DB::table('candidate_reinitiates')->select('created_by')->where(['user_type'=>'candidate','id'=>$job_item->candidate_id])->whereNotNull('created_by')->first();
                // dd($user);
            if ($user!= NULL) {
                # code...

                DB::table('job_items')->where(['jaf_status'=>'filled','candidate_id'=>$job_item->candidate_id])->update(['filled_by'=>$user->created_by]);
                        // dd($user);
            }
        }
       
       
       
        // dd($job_items);
        
    }
    
    public function jafFilledType()
    {
        $job_items = DB::table('job_items')->where(['jaf_status'=>'filled'])->get();

        foreach ($job_items as $job_item) {

            // $user = DB::table('users')->select('created_by')->where(['user_type'=>'candidate','id'=>$job_item->candidate_id])->whereNotNull('created_by')->first();
                // dd($user);
            // if ($user!= NULL) {
                # code...

                DB::table('job_items')->where(['jaf_status'=>'filled','candidate_id'=>$job_item->candidate_id])->update(['filled_by_type'=>'customer']);
                        // dd($user);
            // }
        }
       
       
       
        // dd($job_items);
        
    }

    public function filledAt()
    {
        $job_items = DB::table('job_items')->where(['jaf_status'=>'filled'])->get();

        foreach ($job_items as $job_item) {

            // $user = DB::table('users')->select('created_by')->where(['user_type'=>'candidate','id'=>$job_item->candidate_id])->whereNotNull('created_by')->first();
                // dd($user);
            // if ($user!= NULL) {
                # code...

                DB::table('job_items')->where(['jaf_status'=>'filled','candidate_id'=>$job_item->candidate_id])->update(['filled_at'=>$job_item->created_at]);
                        // dd($user);
            // }
        }
       
       
       
        // dd($job_items);
        
    }

    //For update insuff in job_item table
    public function isInsuff()
    {
        $jaf_form_data = DB::table('jaf_form_data')->where(['is_insufficiency'=>'1'])->get();
        // dd($jaf_form_data);
        foreach ($jaf_form_data as $jfd) {

            $job_item = DB::table('job_items')->where(['candidate_id'=>$jfd->candidate_id,'jaf_status'=>'filled'])->first();
                // dd($job_item);
            if ($job_item->is_insuff == '0') {
                # code...

                DB::table('job_items')->where(['jaf_status'=>'filled','candidate_id'=>$job_item->candidate_id])->update(['is_insuff'=>'1','is_all_insuff_cleared'=>'1','insuff_created_at'=>$job_item->created_at,'insuff_created_by'=>$job_item->filled_by]);
                        // dd($user);
            }
        }
       
             
    }

     //For update report created by in reports table
     public function reportCreatedBy()
     {
        $job_items = DB::table('job_items')->where(['jaf_status'=>'filled'])->get();
         // dd($jaf_form_data);
         foreach ($job_items as $job_item) {
 
             $reports = DB::table('reports')->where(['candidate_id'=>$job_item->candidate_id])->first();
                 // dd($job_item);
            //  if ($job_item->is_insuff == '0') {
                 # code...
 
                 DB::table('reports')->where(['candidate_id'=>$job_item->candidate_id])->update(['created_by'=>$job_item->filled_by]);
                         // dd($user);
            //  }
         }
        
              
     }

     //For update Clear Insuff in jaf form data table
     public function clearInsuff()
     {
        $reports = DB::table('reports')->where(['status'=>'completed'])->get();
        //  dd($reports);
         foreach ($reports as $report) {
 
             $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$report->candidate_id,'is_insufficiency'=>'1'])->get();
                //  echo '<pre>';print_r($jaf_form_data);
            foreach ($jaf_form_data as $jfd) {
                // echo '<pre>';print_r($jfd);
                $jfds = DB::table('jaf_form_data')->where(['candidate_id'=>$report->candidate_id,'is_insufficiency'=>'1'])->update(['is_insufficiency'=>'0']);

            }
                 
         }
        
              
     }

      //For update Raised Insuff in verification_insufficiency table
      public function raisedInsuff()
      {
        $jaf_form_data = DB::table('jaf_form_data as jfd')
        ->select('jfd.*')
        ->join('candidate_reinitiates as u','u.id','=','jfd.candidate_id')
        ->where(['jfd.is_insufficiency'=>'1','u.is_deleted'=>'0'])->get();
        // echo '<pre>';print_r($jaf_form_data);
        //   dd($jaf_form_data);
          foreach ($jaf_form_data as $jfd) 
          {

                $ver_insuff_data=[
                    'business_id' => $jfd->business_id,
                    'candidate_id' => $jfd->candidate_id,
                    'service_id'  => $jfd->service_id,
                    'jaf_form_data_id' => $jfd->id,
                    'item_number' => $jfd->check_item_number,
                    'activity_type'=> 'jaf-insuff',
                    'status'=>'raised',
                    'notes' => $jfd->insufficiency_notes,
                    'created_by'   =>$jfd->created_by,
                    'created_at'   => $jfd->created_at,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
        
                DB::table('verification_insufficiency')->insert($ver_insuff_data);
    
                //       $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$report->candidate_id,'is_insufficiency'=>'1'])->get();
                //          //  echo '<pre>';print_r($jaf_form_data);
                //      foreach ($jaf_form_data as $jfd) {
                        //  echo '<pre>';print_r($jfd);
                //          $jfds = DB::table('jaf_form_data')->where(['candidate_id'=>$report->candidate_id,'is_insufficiency'=>'1'])->update(['is_insufficiency'=>'0']);
        
                //      } 
          }   
      }

    public function customers_list()
    {
        $business_id = Auth::user()->business_id;
        $array_result=[];
        $items = DB::table('users as u')
        ->select('u.id')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->whereNotIn('u.id',[$business_id])->get();
        foreach($items as $item){
            $array_result[]=$item->id;
        }
        // dd($array_result);
        return $array_result;
    }

    public function candidates_list(){
        $array_result=[];
        $customers=$this->customers_list();
        // dd($customers);
        foreach($customers as $cust_id){
            $candidates = DB::table('candidate_reinitiates as u')
            ->select('r.business_id','r.candidate_id','r.status','r.id as report_id','r.parent_id','r.sla_id')
            ->join('reports as r','r.candidate_id','=','u.id')
            ->where(['u.user_type'=>'candidate','r.business_id'=>$cust_id])
            ->whereIn('r.status',['completed'])
            ->get();
            if(count($candidates)>0){
                foreach($candidates as $item){
                    $array_result[]=['report_id'=>$item->report_id,'parent_id'=>$item->parent_id,'business_id'=>$cust_id,'candidate_id'=>$item->candidate_id,'sla_id'=>$item->sla_id];
                }
            }
        }
        // dd($array_result);
        return $array_result;
    }

    public function report_list(){
        $report_list=$this->candidates_list();
        $customers=$this->customers_list();
        $array_result=[];

        $today_date=date('Y-m-d');

        $start_date=date('Y-m-d',strtotime('-15 days'));

        
        // $start_date="2021-04-07";

        // $today_date="2021-04-22";

        // dd($start_date);

        DB::beginTransaction();
        try{
            foreach($report_list as $items){

                $report_items = DB::table('report_items as ri')
                    ->select('ri.report_id as report_id','s.name as service_name','s.id as service_id','ri.service_item_number as service_no','r.created_at','r.parent_id','u.created_at as candidate_creation_date','u.id as candidate_id','r.complete_created_at as completed_date','j.tat_type','j.days_type','j.id as job_item_id','r.status as report_status','r.generated_at','j.incentive as case_incentive','j.incentive as case_penalty','j.client_tat','j.price_type','j.package_price','ri.data_verified_date','ri.jaf_id','ri.additional_charges','ri.additional_charge_notes','ri.is_supplementary','ri.is_charge_allowed')  
                    ->join('reports as r','r.id','=','ri.report_id')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    // ->join('check_prices as c','c.service_id','=','s.id')
                    ->where(['ri.report_id'=>$items['report_id'],'ri.is_data_verified'=>'1']) 
                    ->whereDate('ri.data_verified_date','>=',$start_date)
                    ->whereDate('ri.data_verified_date','<=',$today_date)
                    ->orderBy('r.business_id','asc')
                    ->get();
                    if(count($report_items)>0){   
                        foreach($report_items as $item){
                            $candidate_date = date('Y-m-d',strtotime($item->candidate_creation_date));
                            
                            // if(stripos($item->report_status,'interim')!==false)
                            //     $completed_date = date('Y-m-d',strtotime($item->generated_at));
                            // elseif(stripos($item->report_status,'completed')!==false)
                            //     $completed_date = date('Y-m-d',strtotime($item->completed_date));
                            // else
                            //     $completed_date = date('Y-m-d',strtotime($item->generated_at));

                            $completed_date = date('Y-m-d',strtotime($item->data_verified_date));

                            // dd($completed_date);

                            // $price=20.00;
                            // $data = DB::table('check_price_cocs')->where(['service_id'=>$item->service_id,'coc_id'=>$items['business_id']])->first();
                            // if($data!=NULL)
                            // {
                            //     $price=$data->price;
                            // }
                            // else{
                            //     $data=DB::table('check_prices')->where(['service_id'=>$item->service_id,'business_id'=>$items['parent_id']])->first();
                            //     if($data!=NULL)
                            //     {
                            //         $price=$data->price;
                            //     }
                            //     // else{
                            //     //     $data=DB::table('check_price_masters')->where(['business_id'=>$item->parent_id,'service_id'=>$item->service_id])->first();
                            //     //     if($data!=NULL)
                            //     //     {
                            //     //         $price=$data->price;
                            //     //     }
                            //     // }
                            // }

                            $price = 20;
                            $price_type = $item->price_type;
                              
                            if(stripos($item->tat_type,'case')!==false)
                            {
                                $date_arr=[];
                                $incentive=0;
                                $penalty=0;

                                $job_sla_items=DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id])->first();

                                if(stripos($price_type,'check')!==false)
                                {
                                    $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();

                                    if($job_sla_i!=NULL)
                                    {
                                        $price = $job_sla_i->price;
                                    }
                                    else
                                    {
                                        $price = $job_sla_items->price;
                                    }
                                }
                                else if(stripos($price_type,'package')!==false)
                                {
                                    if($item->is_supplementary==1)
                                    {
                                        $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();

                                        if($job_sla_i!=NULL)
                                        {
                                            $price = $job_sla_i->price;
                                        }
                                        else
                                        {
                                            $price = $job_sla_items->price;
                                        }
                                    }
                                    else
                                    {
                                        $report_item_data = DB::table('report_items')->where('report_id',$item->report_id)->get();
                                        $count = count($report_item_data);
                                        $price = $this->round_up($item->package_price/$count,2);
                                    }
                                }
                                
                                $tat = $item->client_tat - 1;
                                $incentive_tat = $item->client_tat - 1;

                                // check if its a additional check
                                if($item->is_supplementary==1)
                                {
                                    $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();

                                    if($job_sla_i!=NULL)
                                    {
                                        $tat = $job_sla_i->tat - 1;
                                        $incentive_tat = $job_sla_i->incentive_tat - 1;
                                    }
                                }

                                if(stripos($item->days_type,'working')!==false)
                                {
                                    $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                }
                                else if(stripos($item->days_type,'calender')!==false)
                                {
                                    $holiday_master=DB::table('customer_holiday_masters')
                                                    ->distinct('date')
                                                    ->select('date')
                                                    ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                    ->orderBy('date','asc')
                                                    ->get();
                                    if(count($holiday_master)>0)
                                    {
                                        $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                    }
                                    else
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                    }
                                }

                                if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                                {
                                    $incentive = $item->case_incentive;
                                }
                                else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                {
                                    $penalty = $item->case_penalty;
                                }

                                $additional_charge = 0.00;

                                $additional_charge_notes = NULL;

                                if($item->is_charge_allowed==1)
                                {
                                    $additional_charge = $item->additional_charges;

                                    $additional_charge_notes = $item->additional_charge_notes;
                                }

                                $array_result[]=['parent_id'=>$items['parent_id'],'business_id'=>$items['business_id'],'candidate_id'=>$items['candidate_id'],'report_id'=>$item->report_id,'report_status'=>$item->report_status,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_item_no'=>$item->service_no,'price'=>$price,'price_type'=>$price_type,'incentive'=>$incentive,'penalty'=>$penalty,'start_date'=>$start_date,'end_date'=>$today_date,'tat_date'=>$date_arr['tat_date'],'inc_tat_date'=>$date_arr['inc_tat_date'],'jaf_id'=>$item->jaf_id,'is_charge_allowed'=>$item->is_charge_allowed,'additional_charge'=>$additional_charge,'additional_charge_notes'=>$additional_charge_notes];
                                
                            }
                            else if(stripos($item->tat_type,'check')!==false)
                            {
                                $job_sla_items=DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id])->first();

                                if($job_sla_items!=NULL)
                                {
                                    $date_arr=[];
                                    $tat=$job_sla_items->tat - 1;
                                    $incentive_tat = $job_sla_items->incentive_tat - 1;
                                    $incentive=0;
                                    $penalty=0;

                                    // check if its a additional check
                                    if($item->is_supplementary==1)
                                    {
                                        $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();

                                        if($job_sla_i!=NULL)
                                        {
                                            $tat = $job_sla_i->tat - 1;
                                            $incentive_tat = $job_sla_i->incentive_tat - 1;
                                        }
                                    }

                                    if(stripos($price_type,'check')!==false)
                                    {
                                        $price = $job_sla_items->price;
                                    }
                                    else if(stripos($price_type,'package')!==false)
                                    {
                                        if($item->is_supplementary==1)
                                        {
                                            $job_sla_i = DB::table('job_sla_items')->where(['job_item_id'=>$item->job_item_id,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_no,'is_supplementary'=>'1'])->first();

                                            if($job_sla_i!=NULL)
                                            {
                                                $price = $job_sla_i->price;
                                            }
                                            else
                                            {
                                                $price = $job_sla_items->price;
                                            }
                                        }
                                        else
                                        {
                                            $report_item_data = DB::table('report_items')->where('report_id',$item->report_id)->get();
                                            $count = count($report_item_data);
                                            $price = $this->round_up($item->package_price/$count,2);
                                        }
                                    }

                                    if(stripos($item->days_type,'working')!==false)
                                    {
                                        $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                    }
                                    else if(stripos($item->days_type,'calender')!==false)
                                    {
                                        $holiday_master=DB::table('customer_holiday_masters')
                                                        ->distinct('date')
                                                        ->select('date')
                                                        ->where(['business_id'=>$item->parent_id,'status'=>1])
                                                        ->orderBy('date','asc')
                                                        ->get();
                                        if(count($holiday_master)>0)
                                        {
                                            $date_arr = $this->calenderDays($candidate_date,$holiday_master,$tat,$incentive_tat);
                                        }
                                        else
                                        {
                                            $date_arr = $this->workingDays($candidate_date,$tat,$incentive_tat);
                                        }

                                    }

                                    $coc_incentive=DB::table('check_coc_incentives')->where(['coc_id'=>$job_sla_items->business_id,'service_id'=>$job_sla_items->service_id])->first();


                                    if($coc_incentive!=NULL)
                                    {
                                        // dd($date_arr);
                                        //check if task completed date is less than or equal to incentive Date
                                        if(strtotime($completed_date) <= strtotime($date_arr['inc_tat_date']))
                                        {
                                            $incentive = $coc_incentive->incentive;

                                            // dd($incentive);
                                        }
                                        else if(strtotime($completed_date) > strtotime($date_arr['tat_date']))
                                        {
                                            $penalty = $coc_incentive->penalty;
                                        }
                                    }

                                    $additional_charge = 0.00;

                                    $additional_charge_notes = NULL;

                                    if($item->is_charge_allowed==1)
                                    {
                                        $additional_charge = $item->additional_charges;

                                        $additional_charge_notes = $item->additional_charge_notes;
                                    }

                                    $array_result[]=['parent_id'=>$items['parent_id'],'business_id'=>$items['business_id'],'candidate_id'=>$items['candidate_id'],'report_id'=>$item->report_id,'report_status'=>$item->report_status,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_item_no'=>$item->service_no,'price'=>$price,'price_type'=>$price_type,'incentive'=>$incentive,'penalty'=>$penalty,'start_date'=>$start_date,'end_date'=>$today_date,'tat_date'=>$date_arr['tat_date'],'inc_tat_date'=>$date_arr['inc_tat_date'],'jaf_id'=>$item->jaf_id,'is_charge_allowed'=>$item->is_charge_allowed,'additional_charge'=>$additional_charge,'additional_charge_notes'=>$additional_charge_notes];
                                }

                            }

                        }
                    }
                    // $array_result[]=$report_items;
            }

            // dd($array_result);

            // insertion in billing db from report 
            if(count($array_result)>0)
            {
                foreach($array_result as $record)
                {
                    $invoice_no = strtoupper(substr('BCD', 0, 3)).date("-ymds");

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
                            // 'invoice_id'  => 'invoice_no'.Str::random(10),
                            'start_date'  => date('Y-m-d H:i:s',strtotime($start_date)),
                            'end_date'  => date('Y-m-d H:i:s',strtotime($today_date)),
                            'created_at'=> date('Y-m-d H:i:s'),
                        ];
                        $bill=DB::table('billings')->insertGetId($billing_data);

                        DB::table('billings')->where(['id'=>$bill])->update([
                            'invoice_id' => $invoice_no.$bill
                        ]);

                        $billing_item_data=[
                        'billing_id'  => $bill,
                        'parent_id'   => $record['parent_id'],
                        'business_id' => $record['business_id'],
                        'user_id'     => $record['business_id'],
                        'candidate_id'=> $record['candidate_id'],
                        'service_id'  => $record['service_id'],
                        'service_name'=> $record['service_name'],
                        'service_item_number' =>$record['service_item_no'],
                        'quantity'    => 1,
                        'price'       =>$record['price'],
                        'incentive'   =>$record['incentive'],
                        'penalty'     =>$record['penalty'],
                        'tat_date'    => $record['tat_date'],
                        'incentive_tat_date'    => $record['inc_tat_date'],
                        'report_status'     => $record['report_status'],
                        'additional_charges' => $record['additional_charge'],
                        'additional_charge_notes' => $record['additional_charge_notes'],
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                        ];

                        $bill_items=DB::table('billing_items')->insertGetId($billing_item_data);

                        $jaf_add_attachment = DB::table('jaf_additional_charge_attachments')->where(['jaf_id'=>$record['jaf_id']])->get();

                        if(count($jaf_add_attachment)>0 && $record['is_charge_allowed']==1)
                        {
                            foreach($jaf_add_attachment as $attach)
                            {
                                DB::table('billing_additional_charge_attachments')->where(['billing_id'=>$bill,'service_id'=>$attach->service_id,'service_item_number'=>$attach->service_item_number])->delete();

                                $jaf_file_path = public_path().'/uploads/jaf/additional-charge/';

                                $bill_file_path = public_path().'/uploads/billings/additional-charge/';

                                if(File::exists($bill_file_path.$attach->file_name))
                                {
                                    File::delete($bill_file_path.$attach->file_name);
                                }

                                if(File::exists($jaf_file_path.$attach->file_name))
                                {
                                    File::copy($jaf_file_path.$attach->file_name,$bill_file_path.$attach->file_name);
                                }

                                DB::table('billing_additional_charge_attachments')->insert([
                                    'billing_id' => $bill,
                                    'billing_details_id' => $bill_items,
                                    'business_id' => $attach->business_id,
                                    'service_id' => $attach->service_id,
                                    'service_item_number' => $attach->service_item_number,
                                    'file_name' => $attach->file_name,
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }
                    else{
                        DB::table('billing_items')->where(['billing_id'=>$billing_r->id,'candidate_id'=>$record['candidate_id'],'service_id'=>$record['service_id'],'service_item_number'=>$record['service_item_no']])->delete();
                        $billing_item_data1=[
                            'billing_id'  => $billing_r->id,
                            'parent_id'   => $record['parent_id'],
                            'business_id' => $record['business_id'],
                            'user_id'     => $record['business_id'],
                            'candidate_id'=> $record['candidate_id'],
                            'service_id'  => $record['service_id'],
                            'service_name'=> $record['service_name'],
                            'service_item_number' =>$record['service_item_no'],
                            'quantity'    => 1,
                            'price'       =>$record['price'],
                            'incentive'   =>$record['incentive'],
                            'penalty'     =>$record['penalty'],
                            'tat_date'    => $record['tat_date'],
                            'incentive_tat_date'    => $record['inc_tat_date'],
                            'report_status'     => $record['report_status'],
                            'additional_charges' => $record['additional_charge'],
                            'additional_charge_notes' => $record['additional_charge_notes'],
                            'created_at'=> date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        ];
                        $bill_items1=DB::table('billing_items')->insertGetId($billing_item_data1);

                        $jaf_add_attachment = DB::table('jaf_additional_charge_attachments')->where(['jaf_id'=>$record['jaf_id']])->get();

                        if(count($jaf_add_attachment)>0 && $record['is_charge_allowed']==1)
                        {
                            foreach($jaf_add_attachment as $attach)
                            {
                                DB::table('billing_additional_charge_attachments')->where(['billing_id'=>$billing_r->id,'service_id'=>$attach->service_id,'service_item_number'=>$attach->service_item_number])->delete();

                                $jaf_file_path = public_path().'/uploads/jaf/additional-charge/';

                                $bill_file_path = public_path().'/uploads/billings/additional-charge/';

                                if(File::exists($bill_file_path.$attach->file_name))
                                {
                                    File::delete($bill_file_path.$attach->file_name);
                                }

                                if(File::exists($jaf_file_path.$attach->file_name))
                                {
                                    File::copy($jaf_file_path.$attach->file_name,$bill_file_path.$attach->file_name);
                                }
                                
                                DB::table('billing_additional_charge_attachments')->insert([
                                    'billing_id' => $billing_r->id,
                                    'billing_details_id' => $bill_items1,
                                    'business_id' => $attach->business_id,
                                    'service_id' => $attach->service_id,
                                    'service_item_number' => $attach->service_item_number,
                                    'file_name' => $attach->file_name,
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }
                }
            }

            // dd($array_result);

            $api_array=$this->apiPrice($start_date,$today_date);

            // dd($api_array);

            if(count($api_array)>0)
            {
                foreach($api_array as $record)
                {
                    $invoice_no = strtoupper(substr('BCD', 0, 3)).date("-ymds");

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
                        // 'invoice_id'  => 'invoice_no'.Str::random(10),
                        'start_date'  => date('Y-m-d H:i:s',strtotime($start_date)),
                        'end_date'  => date('Y-m-d H:i:s',strtotime($today_date)),
                        'created_at'=> date('Y-m-d H:i:s'),
                        ];
                        
                        $bill=DB::table('billings')->insertGetId($billing_data);

                        DB::table('billings')->where(['id'=>$bill])->update([
                            'invoice_id' => $invoice_no.$bill
                        ]);

                        $billing_item_data=[
                        'billing_id'  => $bill,
                        'parent_id'   => $record['parent_id'],
                        'business_id' => $record['business_id'],
                        'user_id'     => $record['business_id'],
                        'service_id'  => $record['service_id'],
                        'service_name'=> $record['service_name'],
                        'service_data'=> json_encode($record['service_data']),
                        'service_item_number' =>1,
                        'quantity'    => $record['qty'],
                        'price'       =>$record['price'],
                        'tat_date'    => $record['tat_date'],
                        'incentive_tat_date'    => $record['incentive_tat_date'],
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s')
                        ];
                        $bill_items=DB::table('billing_items')->insert($billing_item_data);
                    }
                    else{
                        DB::table('billing_items')->where(['billing_id'=>$billing_r->id,'service_data'=>json_encode($record['service_data'])])->delete();
                        $billing_item_data1=[
                            'billing_id'  => $billing_r->id,
                            'parent_id'   => $record['parent_id'],
                            'business_id' => $record['business_id'],
                            'user_id'     => $record['business_id'],
                            'service_id'  => $record['service_id'],
                            'service_name'=> $record['service_name'],
                            'service_data'=> json_encode($record['service_data']),
                            'service_item_number' =>1,
                            'quantity'    => $record['qty'],
                            'price'       =>$record['price'],
                            'tat_date'    => $record['tat_date'],
                            'incentive_tat_date'    => $record['incentive_tat_date'],
                            'created_at'=> date('Y-m-d H:i:s'),
                            'updated_at'=> date('Y-m-d H:i:s')
                        ];
                        $bill_items1=DB::table('billing_items')->insert($billing_item_data1);
                    }
                }
            }

            // Calculating the total price of billing items
            if(count($array_result) > 0 || count($api_array) >0)
            {
                foreach($customers as $cust_id)
                {
                    $tax = 0;

                    $tax_amount = 0;

                    $billing_record=DB::table('billings as b')
                        ->select('bi.price','bi.incentive','bi.penalty','bi.id','bi.additional_charges')
                        ->join('billing_items as bi','b.id','=','bi.billing_id')
                        ->whereDate('b.start_date','=',$start_date)
                        ->whereDate('b.end_date','=',$today_date)
                        ->where(['bi.business_id'=>$cust_id])
                        ->orderBy('b.business_id','asc')
                        ->get();
                    if(count($billing_record)>0)
                    {
                        $total_price=0;

                        $sub_total_price=0;

                        foreach($billing_record as $record)
                        {
                            $price = 0;

                            $price = $record->price;

                            $price = number_format($price + ($price * ($record->incentive/100)),2);

                            $price = number_format($price - ($price * ($record->penalty/100)),2);

                            $price = number_format($price + $record->additional_charges,2);

                            DB::table('billing_items')->where(['id'=>$record->id])->update([
                                'total_check_price' => $price,
                                'final_total_check_price' => $price,
                            ]);

                            $sub_total_price = $total_price + $price;

                            $total_price= $sub_total_price;
                        }

                        $user_business=DB::table('user_businesses')->where(['business_id'=>$cust_id])->first();

                        if($user_business!=NULL)
                        {
                            if($user_business->gst_exempt==0)
                            {
                                $tax= 18.00;
                                $tax_amount = number_format(str_replace(",","",number_format($total_price * ($tax/100),2)),2,".","");

                                $total_price = $total_price +  $tax_amount;
                            }
                        }
                        DB::table('billings as b')
                        ->where(['b.business_id'=>$cust_id])
                        ->whereDate('b.start_date','=',$start_date)
                        ->whereDate('b.end_date','=',$today_date)
                        ->update([
                            'tax' => $tax,
                            'tax_amount' => $tax_amount,
                            'sub_total' => $sub_total_price,
                            'total_amount'=>  $total_price,
                            'updated_at'=> date('Y-m-d H:i:s')
                        ]);

                        // $users = DB::table('users as u')
                        // ->where(['u.id'=>$cust_id,'u.user_type'=>'client'])
                        // ->first();

                        // $bill=DB::table('billings')
                        //         ->where(['b.business_id'=>$cust_id])
                        //         ->whereDate('b.start_date','=',$start_date)
                        //         ->whereDate('b.end_date','=',$today_date)
                        //         ->first();

                        // $email = $users->email;
                        // $name  = $users->first_name;
                        // $business_id = $users->parent_id;
                        // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        
                        // $data  = array('name'=>$name,'email'=>$email,'user'=>$users,'bill'=>$bill,'sender'=>$sender);

                        // Mail::send(['html'=>'mails.billing-notify'], $data, function($message) use($email,$name) {
                        //     $message->to($email, $name)->subject
                        //         ('myBCD System - Billing Notification');
                        //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        // });
                    }
                }
            }

            DB::commit();
            dd($array_result);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }
    

    public function apiPrice($start_date,$end_date)
    {
        $parent_id=Auth::user()->business_id;
        $customers=$this->customers_list();

        $incentive=0;
        $penalty=0;

        $array_result=[];
        foreach($customers as $cust_id)
        {
            
            $aadhars=DB::table('aadhar_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.aadhar_number')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($aadhars)>0)
            {
                foreach($aadhars as $aadhar)
                {
                    $data=[];
                    $data=['Aadhar Number'=> $aadhar->aadhar_number];
                    $array_result[]=['parent_id'=>$aadhar->parent_id,'business_id'=>$cust_id,'service_id'=>$aadhar->service_id,'service_name'=>$aadhar->service_name,'service_data'=>$data,'price'=>$aadhar->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $pans=DB::table('pan_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.pan_number')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($pans)>0)
            {
                foreach($pans as $pan)
                {
                    $data=[];
                    $data=['PAN Number'=> $pan->pan_number];
                    $array_result[]=['parent_id'=>$pan->parent_id,'business_id'=>$cust_id,'service_id'=>$pan->service_id,'service_name'=>$pan->service_name,'service_data'=>$data,'price'=>$pan->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $voter_ids=DB::table('voter_id_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.voter_id_number')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_reference'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($voter_ids)>0)
            {
                foreach($voter_ids as $voter_id)
                {
                    $data=[];
                    $data=['Voter ID Number'=> $voter_id->voter_id_number];
                    $array_result[]=['parent_id'=>$voter_id->parent_id,'business_id'=>$cust_id,'service_id'=>$voter_id->service_id,'service_name'=>$voter_id->service_name,'service_data'=>$data,'price'=>$voter_id->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }
        
            $rcs=DB::table('rc_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.rc_number')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($rcs)>0)
            {
                foreach($rcs as $rc)
                {
                    $data=[];
                    $data=['RC Number'=> $rc->rc_number];
                    $array_result[]=['parent_id'=>$rc->parent_id,'business_id'=>$cust_id,'service_id'=>$rc->service_id,'service_name'=>$rc->service_name,'service_data'=>$data,'price'=>$rc->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $dls=DB::table('dl_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.dl_number')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($dls)>0)
            {
                foreach($dls as $dl)
                {
                    $data=[];
                    $data=['DL Number'=> $dl->dl_number];
                    $array_result[]=['parent_id'=>$dl->parent_id,'business_id'=>$cust_id,'service_id'=>$dl->service_id,'service_name'=>$dl->service_name,'service_data'=>$data,'price'=>$dl->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $passports=DB::table('passport_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.file_number','a.dob')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($passports)>0)
            {
                foreach($passports as $passport)
                {
                    $data=[];
                    $data=['File Number'=> $passport->file_number,'DOB'=>$passport->dob];
                    $array_result[]=['parent_id'=>$passport->parent_id,'business_id'=>$cust_id,'service_id'=>$passport->service_id,'service_name'=>$passport->service_name,'service_data'=>$data,'price'=>$passport->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $banks=DB::table('bank_account_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.account_number','a.ifsc_code')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','a.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($banks)>0)
            {
                foreach($banks as $bank)
                {
                    $data=[];
                    $data=['Account Number'=> $bank->account_number,'IFSC Code'=>$bank->ifsc_code];
                    $array_result[]=['parent_id'=>$bank->parent_id,'business_id'=>$cust_id,'service_id'=>$bank->service_id,'service_name'=>$bank->service_name,'service_data'=>$data,'price'=>$bank->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $gsts=DB::table('gst_checks as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.gst_number')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();

            if(count($gsts)>0)
            {
                foreach($gsts as $gst)
                {
                    $data=[];
                    $data=['GST Number'=> $gst->gst_number];
                    $array_result[]=['parent_id'=>$gst->parent_id,'business_id'=>$cust_id,'service_id'=>$gst->service_id,'service_name'=>$gst->service_name,'service_data'=>$data,'price'=>$gst->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $telecoms=DB::table('telecom_check as a')
            ->select('a.parent_id','s.id as service_id','s.name as service_name','a.price','a.mobile_no')
            ->join('services as s','s.id','=','a.service_id')
            // ->join('check_prices as c','c.service_id','=','a.service_id')
            ->where(['source_type'=>'API','a.used_by'=>'coc','a.business_id'=>$cust_id])
            ->whereDate('a.created_at','>=',$start_date)
            ->whereDate('a.created_at','<=',$end_date)
            ->whereNULL('a.candidate_id')
            // ->groupBy('a.service_id')
            ->get();
            
            if(count($telecoms)>0)
            {
                foreach($telecoms as $telecom)
                {
                    $data=[];
                    $data=['Mobile Number'=> $telecom->mobile_no];
                    $array_result[]=['parent_id'=>$telecom->parent_id,'business_id'=>$cust_id,'service_id'=>$telecom->service_id,'service_name'=>$telecom->service_name,'service_data'=>$data,'price'=>$telecom->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }
            
            $e_courts=DB::table('e_court_checks as a')
                    ->select('s.id as service_id','s.name as service_name','a.name','a.father_name','a.address','a.user_id','a.created_at','a.price','a.parent_id')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$cust_id])
                    ->whereDate('a.created_at','>=',$start_date)
                    ->whereDate('a.created_at','<=',$end_date)
                    ->whereNULL('a.candidate_id')
                    ->get();


            if(count($e_courts)>0)
            {
                foreach($e_courts as $item)
                {
                    $data = [];

                    $data=['Name'=>$item->name,'Father Name'=>$item->father_name,'Address'=>$item->address];

                    $array_result[]=['parent_id'=>$item->parent_id,'business_id'=>$cust_id,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_data'=>$data,'price'=>$item->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $upis=DB::table('upi_checks as a')
                    ->select('s.id as service_id','s.name as service_name','a.name','a.upi_id','a.user_id','a.created_at','a.price','a.parent_id')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$cust_id])
                    ->whereDate('a.created_at','>=',$start_date)
                    ->whereDate('a.created_at','<=',$end_date)
                    ->whereNULL('a.candidate_id')
                    ->get();

            if(count($upis)>0)
            {
                foreach($upis as $item)
                {
                    $data = [];

                    $data=['UPI ID'=>$item->upi_id,'Name'=>$item->name];

                    $array_result[]=['parent_id'=>$item->parent_id,'business_id'=>$cust_id,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_data'=>$data,'price'=>$item->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }

            $cins=DB::table('cin_checks as a')
                        ->select('s.id as service_id','s.name as service_name','a.company_name','a.cin_number','a.user_id','a.created_at','a.price','a.parent_id')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$cust_id])
                        ->whereDate('a.created_at','>=',$start_date)
                        ->whereDate('a.created_at','<=',$end_date)
                        ->whereNULL('a.candidate_id')
                        ->get();

            if(count($cins)>0)
            {
                foreach($cins as $item)
                {
                    $data = [];

                    $data=['CIN Number'=>$item->cin_number,'Company Name'=>$item->company_name];

                    $array_result[]=['parent_id'=>$item->parent_id,'business_id'=>$cust_id,'service_id'=>$item->service_id,'service_name'=>$item->service_name,'service_data'=>$data,'price'=>$item->price,'incentive'=>$incentive,'penalty'=>$penalty,'tat_date'=>NULL,'incentive_tat_date'=>NULL,'start_date'=>$start_date,'end_date'=>$end_date,'qty'=>1];
                }
            }
        }

       return $array_result;
    }

    //for update parent_id in task 
    public function taskParentUpdate()
    {
        $tasks = Task::whereNull('parent_id')->get();
        if (count($tasks)>0) {

            foreach ($tasks as $task) {
                
                $business_id = $task->business_id;
                $user = User::where('business_id',$business_id)->first();

                $task->parent_id = $user->parent_id;
                $task->save();
            }
            
        }
        $task_assignments= TaskAssignment::whereNull('parent_id')->get();
        if (count($task_assignments)>0) {

            foreach ($task_assignments as $task_assignment) {
                
                $business_id = $task_assignment->business_id;
                $user = User::where('business_id',$business_id)->first();
                DB::table('task_assignments')->whereNull('parent_id')->update(['parent_id'=>$user->parent_id]);
                // $task_assignment->parent_id = $user->parent_id;
                // $task_assignment->save();
            }
            
        }

    }

    public function taskupdateatUpdate()
    {
        
        $task_assignments= TaskAssignment::whereNull('updated_at')->get();
        if (count($task_assignments)>0) {

            foreach ($task_assignments as $task_assignment) {
                
                // $updated_at = $task_assignment->created_at;
                // $user = User::where('business_id',$business_id)->first();

                $task_assignment->updated_at = $task_assignment->created_at;
                $task_assignment->save();
            }
            
        }

    }

    public function report_status()
    {
        $business_id=Auth::user()->business_id;
        $job_items = DB::table('users as u')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','j.jaf_status'=>'filled'])->get();

            if(count($job_items)>0)
            {
                foreach ($job_items as $job)
                {
                    //check report items created or not
                    $report_count = DB::table('reports')->where(['candidate_id'=>$job->candidate_id])->count(); 
                    if($report_count == 0){ 
                    
                        $data = 
                            [
                            'parent_id'     =>$business_id,
                            'business_id'   =>$job->business_id,
                            'candidate_id'  =>$job->candidate_id,
                            'sla_id'        =>$job->sla_id,       
                            'created_at'    =>date('Y-m-d H:i:s')
                            ];
                            
                            $report_id = DB::table('reports')->insertGetId($data);
                            
                            // add service items
                            $jaf_item = DB::table('jaf_form_data')->where(['candidate_id'=>$job->candidate_id])->get(); 

                            foreach($jaf_item as $item){
                                if ($item->verification_status == 'success') {
                                    $data = 
                                    [
                                    'report_id'     =>$report_id,
                                    'service_id'    =>$item->service_id,
                                    'service_item_number'=>$item->check_item_number,
                                    'candidate_id'  =>$job->candidate_id,      
                                    'jaf_data'      =>$item->form_data,
                                    'jaf_id'        =>$item->id,
                                    'created_at'    =>date('Y-m-d H:i:s')
                                    ];
                                } else {
                                    $data = 
                                    [
                                    'report_id'     =>$report_id,
                                    'service_id'    =>$item->service_id,
                                    'service_item_number'=>$item->check_item_number,
                                    'candidate_id'  =>$job->candidate_id,      
                                    'jaf_data'      =>$item->form_data,
                                    'jaf_id'        =>$item->id,
                                    'is_report_output' => '0',
                                    'created_at'    =>date('Y-m-d H:i:s')
                                    ]; 
                                }
                            
                                $report_item_id = DB::table('report_items')->insertGetId($data);
                            }
                    }
                }
            }
            
    }

    public function user_updates()
    {
        DB::table('users')->update([
            'phone_code' => '91',
            'phone_iso' => 'in'
        ]);

        DB::table('user_businesses')->update([
            'phone_code' => '91',
            'phone_iso' => 'in'
        ]);

        DB::table('user_business_contacts')->update([
            'phone_code' => '91',
            'phone_iso' => 'in'
        ]);
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
            DB::table('customer_holiday_masters')->where('type','public')->delete();

            DB::table('customer_holiday_masters')
                        ->where('type','custom')
                        ->whereYear('date','<>',date('Y'))
                        ->delete();
            foreach($array_data['items'] as $key => $item)
            {
                if(date('Y')==date('Y',strtotime($item["start"]['date'])))
                {
                    // $array_result[]=['summary'=>$item['summary'],'date'=>$item['start']['date']];

                    DB::table('customer_holiday_masters')->insert([
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

    public function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        $arr=['tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date];

        return $arr;
        
    }

    public function calenderDays($start_date,$holidays,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        // $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        foreach($holidays as $holiday)
        {
        
            $holiday_ts = strtotime($holiday->date);

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $tat_new_date = date('Y-m-d', strtotime("{$tat_new_date} + 1 weekdays"));
                }
            }

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($inc_tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $inc_tat_new_date = date('Y-m-d', strtotime("{$inc_tat_new_date} + 1 weekdays"));
                }
            }
        }

        return array('tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date);
    }

    //Update customer Updated_by in users table 
    
    // public function billApprove()
    // {
    //     $users= DB::table('users')->where('user_type','customer')->orderBy('id','desc')->get();

    //     foreach ($users as $user)
    //     {
    //         $billings = DB::table('billings')->where(['parent_id'=>$user->id])->get();

    //         if(count($billings)>0)
    //         {
    //             foreach($billings as $billing)
    //             {
    //                 if(stripos($billing->status,'under_review')!==false)
    //                 {
    //                     // dd($billing);
    //                     $billing_approval=DB::table('billing_approvals')->where(['billing_id'=>$billing->id])->latest()->first();

    //                     if($billing_approval!=NULL)
    //                     {
    //                         if($billing_approval->request_cancel_by==NULL && ($billing_approval->request_approve_by_cust_id==NULL || $billing_approval->request_approve_by_coc_id==NULL))
    //                         {
    //                             $request_date = date('Y-m-d',strtotime($billing_approval->request_sent_at));

    //                             $request_end_date = date('Y-m-d',strtotime($billing_approval->request_sent_at.'+7 weekdays'));

    //                             if(strtotime(date('Y-m-d')) > strtotime($request_end_date))
    //                             {
    //                                 $user_d=DB::table('users')->where(['id'=>$billing->business_id])->first();

    //                                 $billing_data = DB::table('billings')->where(['id'=>$billing->id])->first();
    //                                 $name=$user_d->first_name.' '.$user_d->last_name;

    //                                 $email=$user_d->email;

                                        // $business_id = $user_d->parent_id;

                                        // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                            
    //                                 $data=['name' =>$name,'email' => $email,'user'=>$user_d,'billing'=>$billing_data,'sender'=>$sender];
                            
    //                                 Mail::send(['html'=>'mails.billing-approval'], $data, function($message) use($email,$name) {
    //                                     $message->to($email, $name)->subject
    //                                         ('myBCD System - Billing System Notification');
    //                                     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
    //                                 });

    //                                 DB::table('notifications')->insert([
    //                                     'parent_id' => $user_d->parent_id,
    //                                     'business_id' => $user_d->business_id,
    //                                     'user_id' => $user_d->id,
    //                                     'title'    => 'Billing Summary',
    //                                     'message'   => 'You have a pending approval of Billing, that sent by '.Helper::company_name($user_d->parent_id).'. It seems like you have to review once. then do the required action. If you have done already ignore it.',
    //                                     'created_by'   => $user_d->parent_id,
    //                                     'module_id'     => $billing->id,
    //                                     'module_type'   => 'billing',
    //                                     'created_at'    => date('Y-m-d H:i:s')
    //                                 ]);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     echo '1';
    // }

    public function cocUpdatedBy()
    {
        $customers= DB::table('users')->where('user_type','client')->get();

        foreach ($customers as $customer) {
           
            DB::table('users')->where('id',$customer->id)->update(['updated_by'=>Auth::user()->business_id]);
        }
    }

    //Update customer  Created_by in users table 

    public function cocCreatedBy()
    {
        $customers= DB::table('users')->where('user_type','client')->get();

        foreach ($customers as $customer) {
           
            DB::table('users')->where('id',$customer->id)->update(['created_by'=>Auth::user()->business_id]);
        }
    }

     //Update Hold Candidates  Parent_id in candidate_hold_statuses table 

     public function holdCandidateParentId()
     {
         $candidates= DB::table('candidate_hold_statuses')->whereNull('parent_id')->get();
 
         foreach ($candidates as $candidate) {
            $parent_id= DB::table('candidate_reinitiates')->select('parent_id')->where('id',$candidate->candidate_id)->first();
            
             DB::table('candidate_hold_statuses')->where('id',$candidate->id)->update(['parent_id'=>$parent_id->parent_id]);
         }
     }
     public function billApprove()
     {
         $users= DB::table('users')->where('user_type','customer')->orderBy('id','desc')->get();
 
         foreach ($users as $user)
         {
             $billings = DB::table('billings')->where(['parent_id'=>$user->id])->get();
 
             if(count($billings)>0)
             {
                 foreach($billings as $billing)
                 {
                     if(stripos($billing->status,'under_review')!==false)
                     {
                         $today_date = date('Y-m-d');
                         // dd($billing);
                         $billing_approval=DB::table('billing_approval_cocs')->where(['billing_id'=>$billing->id])->latest()->first();
 
                         if($billing_approval!=NULL)
                         {
                             if($billing_approval->request_cancel_by==NULL && $billing_approval->request_approve_by==NULL)
                             {
                                 $request_date = date('Y-m-d',strtotime($billing_approval->request_sent_at));

                                 $client_business = DB::table('user_businesses')
                                                        ->select('bill_action_notify_days as no_of_days')
                                                        ->where('business_id',$billing->business_id)
                                                        ->first();
 
                                 $request_end_date = date('Y-m-d',strtotime($billing_approval->request_sent_at.'+'.$client_business->no_of_days.' weekdays'));
 
                                 if(strtotime($today_date) > strtotime($request_end_date))
                                 {
                                     $user_d=DB::table('users')->where(['id'=>$billing->business_id])->first();
 
                                     $billing_data = DB::table('billings')->where(['id'=>$billing->id])->first();
                                     
                                     $name=$user_d->name;
 
                                     $email=$user_d->email;
                             
                                     $data=['name' =>$name,'email' => $email,'user'=>$user_d,'billing'=>$billing_data];
                             
                                     Mail::send(['html'=>'mails.billing-approval'], $data, function($message) use($email,$name) {
                                         $message->to($email, $name)->subject
                                             ('BCD- Billing System Notification');
                                         $message->from(env('MAIL_FROM_ADDRESS'),'BCD System');
                                     });
 
                                     DB::table('notifications')->insert([
                                         'parent_id' => $user_d->parent_id,
                                         'business_id' => $user_d->business_id,
                                         'user_id' => $user_d->id,
                                         'title'    => 'Billing Summary',
                                         'message'   => 'You have a pending approval of Billing, that sent by '.Helper::company_name($user_d->parent_id).'. It seems like you have to review once. then do the required action. If you have done already ignore it.',
                                         'created_by'   => $user_d->parent_id,
                                         'module_id'     => $billing->id,
                                         'module_type'   => 'billing',
                                         'created_at'    => date('Y-m-d H:i:s')
                                     ]);
                                 }
                             }
                         }
                     }
                 }
             }
         }
 
         echo '1';
     }
 
     public function insuffNotify()
     {
         $users= DB::table('users')->where('user_type','client')->orderBy('id','desc')->get();
 
         foreach($users as $user)
         {
             $parent_id = $user->parent_id;

             $insuff_control=DB::table('coc_insuff_controls')->where(['business_id'=>$user->id])->first();
 
             if($insuff_control!=NULL)
             {
                 $today_date = date('Y-m-d',strtotime('2021-08-14'));
 
                 $start_date = date('Y-m-d',strtotime($today_date.'-'.$insuff_control->days.'days'));
 
                 $path=public_path().'/uploads/insuff-notify/';

                 if (!File::exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
 
                 $file_name="insuff-notify-".date('Ymdhis').".pdf";
 
                 $insuff_notify_log=DB::table('notification_logs')->where('business_id',$user->id)->latest()->first();
 
                 if($insuff_notify_log!=NULL)
                 {
                     $next_end_date = date('Y-m-d',strtotime($insuff_notify_log->end_date.'+'.$insuff_control->days.'days'));
 
                     if(strtotime($today_date)==strtotime($next_end_date))
                     {
                         $insuff_log=DB::table('insufficiency_logs as si')
                                     ->select('si.*','s.verification_type','s.name')
                                     ->join('services as s','s.id','=','si.service_id')
                                     ->where('coc_id',$user->id)
                                     ->whereIn('si.status',['raised','failed'])
                                     ->whereDate('si.created_at','>=',$start_date)
                                     ->whereDate('si.created_at','<=',date('Y-m-d',strtotime($today_date.'-1 days')))
                                     ->orderBy('si.created_at','desc')
                                     ->get();
                     
                             if(count($insuff_log)>0)
                             {
                                 $data=[
                                     'business_id' => $user->id,
                                     'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                     'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                     'days' => $insuff_control->days,
                                     'module_type' => 'insuff_logs'
                                 ];
             
                                 $insuff_id=DB::table('notification_logs')->insertGetId($data);
 
                                 if($insuff_control->status==1)
                                 {
                                     $pdf = PDF::loadView('admin.accounts.insufficiency.pdf.insuff-notify', compact('insuff_log'),[],[
                                         'title' => 'Insufficiency Details',
                                         'margin_top' => 20,
                                         'margin-header'=>20,
                                         'margin_bottom' =>25,
                                         'margin_footer'=>5,
                                     ])->save($path.$file_name);
                                     
                                     DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                         'file_name' => $file_name,
                                         'record_status' => 'found',
                                         'updated_at' => date('Y-m-d H:i:s')
                                     ]);
             
                                     DB::table('notifications')->insert(
                                         [
                                             'parent_id' => $user->parent_id,
                                             'business_id' => $user->id,
                                             'title' => 'Insufficiency Notification',
                                             'message' => 'You have Receive the insufficiency Record, Please checkout the details for further Updates.',
                                             'module_id' => $insuff_id,
                                             'created_by'   => $user->parent_id,
                                             'module_type' => 'notification_logs',
                                             'created_at' => date('Y-m-d H:i:s')
                                         ]
                                     );
                                     $user_d=DB::table('users')->where(['id'=>$user->id])->first();
             
                                     $insuff_log_data = DB::table('notification_logs')->where(['id'=>$insuff_id])->first();
             
                                     $name=$user_d->first_name.' '.$user_d->last_name;
             
                                     $email=$user_d->email;

                                     $sender_d = DB::table('users')->where(['id'=>$parent_id])->first();
                             
                                     $data=['name' =>$name,'email' => $email,'user'=>$user_d,'insuff_log'=>$insuff_log_data,'sender'=>$sender_d];
             
                                     Mail::send(['html'=>'mails.insuff_notification'], $data, function($message) use($email,$name) {
                                         $message->to($email, $name)->subject
                                             ('myBCD System- Insufficiency Notification');
                                         $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                     });
 
                                 }
                                 else
                                 {
                                     DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                         'record_status' => 'disable',
                                         'updated_at' => date('Y-m-d H:i:s')
                                     ]);
                                 }
                             }
                             else
                             {
                                 $data=[
                                     'business_id' => $user->id,
                                     'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                     'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                     'record_status' => 'not_found',
                                     'days' => $insuff_control->days,
                                     'module_type' => 'insuff_logs'
                                 ];
             
                                 $insuff_id=DB::table('notification_logs')->insertGetId($data);
 
                             }
                     }
                 }
                 else
                 {
                     $insuff_log=DB::table('insufficiency_logs as si')
                                     ->select('si.*','s.verification_type','s.name')
                                     ->join('services as s','s.id','=','si.service_id')
                                     ->where('coc_id',$user->id)
                                     ->whereIn('si.status',['raised','failed'])
                                     ->whereDate('si.created_at','>=',$start_date)
                                     ->whereDate('si.created_at','<=',date('Y-m-d',strtotime($today_date.'-1 days')))
                                     ->orderBy('si.created_at','desc')
                                     ->get();
                     
                     if(count($insuff_log)>0)
                     {
                         $data=[
                             'business_id' => $user->id,
                             'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                             'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                             'days' => $insuff_control->days,
                             'module_type' => 'insuff_logs'
                         ];
     
                         $insuff_id=DB::table('notification_logs')->insertGetId($data);
 
                         if($insuff_control->status==1)
                         {
                             $pdf = PDF::loadView('admin.accounts.insufficiency.pdf.insuff-notify', compact('insuff_log'),[],[
                                 'title' => 'Insufficiency Details',
                                 'margin_top' => 20,
                                 'margin-header'=>20,
                                 'margin_bottom' =>25,
                                 'margin_footer'=>5,
                             ])->save($path.$file_name);
                             
                             DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                 'file_name' => $file_name,
                                 'record_status' => 'found',
                                 'updated_at' => date('Y-m-d H:i:s')
                             ]);
     
                             DB::table('notifications')->insert(
                                 [
                                     'parent_id' => $user->parent_id,
                                     'business_id' => $user->id,
                                     'title' => 'Insufficiency Notification',
                                     'message' => 'You have Receive the insufficiency Record, Please checkout the details for further Updates.',
                                     'module_id' => $insuff_id,
                                     'created_by'   => $user->parent_id,
                                     'module_type' => 'notification_logs',
                                     'created_at' => date('Y-m-d H:i:s')
                                 ]
                             );
                             $user_d=DB::table('users')->where(['id'=>$user->id])->first();
     
                             $insuff_log_data = DB::table('notification_logs')->where(['id'=>$insuff_id])->first();
     
                             $name=$user_d->first_name.' '.$user_d->last_name;
     
                             $email=$user_d->email;

                             $sender_d = DB::table('users')->where(['id'=>$parent_id])->first();
                     
                             $data=['name' =>$name,'email' => $email,'user'=>$user_d,'insuff_log'=>$insuff_log_data,'sender'=>$sender_d];
     
                             Mail::send(['html'=>'mails.insuff_notification'], $data, function($message) use($email,$name) {
                                 $message->to($email, $name)->subject
                                     ('myBCD System - Insufficiency Notification');
                                 $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                             });
 
                         }
                         else
                         {
                             DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                 'record_status' => 'disable',
                                 'updated_at' => date('Y-m-d H:i:s')
                             ]);
                         }
 
                     }
                     else
                     {
                         $data=[
                             'business_id' => $user->id,
                             'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                             'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                             'record_status' => 'not_found',
                             'days' => $insuff_control->days,
                             'module_type' => 'insuff_logs'
                         ];
     
                         $insuff_id=DB::table('notification_logs')->insertGetId($data);
 
                     }
                 }
             }
         }
 
         dd(1);
     }
 
     
 
     // public function path_ex()
     // {
     //     $path = public_path().'/uploads/signature/2021053800signature.png';
 
     //     $result=$this->recursiveChmod($path);
 
     //     if($result)
     //     {
     //         return "success";
     //     }
     //     else
     //     {
     //         return "failed";
     //     }
     // }
 
 
     // public function recursiveChmod($path, $filePerm=0777, $dirPerm=0777) {
     //     // Check if the path exists
     //     if (!file_exists($path)) {
     //         return(false);
     //     }
  
     //     // See whether this is a file
     //     if (is_file($path)) {
     //         // Chmod the file with our given filepermissions
     //         chmod($path, $filePerm);
  
     //     // If this is a directory...
     //     } elseif (is_dir($path)) {
     //         // Then get an array of the contents
     //         $foldersAndFiles = scandir($path);
  
     //         // Remove "." and ".." from the list
     //         $entries = array_slice($foldersAndFiles, 2);
  
     //         // Parse every result...
     //         foreach ($entries as $entry) {
     //             // And call this function again recursively, with the same permissions
     //             $this->recursiveChmod($path."/".$entry, $filePerm, $dirPerm);
     //         }
  
     //         // When we are done with the contents of the directory, we chmod the directory itself
     //         chmod($path, $dirPerm);
     //     }
  
     //     // Everything seemed to work out well, return true
     //     return(true);
     // }
    //     // Everything seemed to work out well, return true
    //     return(true);
    // }

    public function round_up ( $value, $precision ) { 
        $pow = pow ( 10, $precision ); 
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
    }
    
    

    public function noDataFound()
    {
        return view('main-web.error-404-data');
    }

    /**
     * Get the user type list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userList(Request $request)
    {
        $user_type = $request->user_type;

        $business_id = Auth::user()->business_id;

        $users = DB::table('users as u')
                    ->select('u.id','u.name','ub.company_name')
                    ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                    ->where(['u.is_deleted'=>0]);
                    if(stripos($user_type,'client')!==false)
                    {
                        $users->where(['u.parent_id'=>$business_id,'u.user_type'=>'client']);
                    }
                    elseif(stripos($user_type,'user')!==false)
                    {
                        $users->where(['u.business_id'=>$business_id,'u.user_type'=>'user'])->orderBy('u.id','desc');
                    }
                    else
                    {
                        $users->where(['u.business_id'=>$business_id,'u.user_type'=>'user'])->orderBy('u.id','desc');
                    }
        $users=$users->get();

            return response()->json([
                'success'   =>true,
                'data'      =>$users
            ]);
    }

     /**
     * Get the user type list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reportAdd(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $parent_id = Auth::user()->parent_id;
        $users= DB::table('users')->where(['parent_id'=>$business_id,'user_type'=>'client','is_deleted'=>0])->get();
        foreach($users as $user)
        {
          $report_add=  DB::table('report_add_page_statuses')->where('coc_id',$user->business_id)->first();
          if($report_add==NULL){
                $data=[
 
                        'parent_id' => $parent_id,
                        'business_id' => $business_id,
                        'coc_id' => $user->business_id,
                        'status' => 'enable',
                        'enable_by' => Auth::user()->id,
                        'template_type' =>'1',
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                  
                    DB::table('report_add_page_statuses')->insert($data);
          }

        }

    }

    public function s3FileUpload(Request $request)
    {
        
        $s3_config = S3ConfigTrait::s3Config();
        
        if($s3_config!=NULL)
        {
            $url = 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/upload/logo.png';

            if(!Storage::disk('s3')->exists('upload'))
            {
                Storage::disk('s3')->makeDirectory('upload',0777, true, true);
            }

            $path = public_path().'/admin/images/';

            $file_name =  'logo.png';   

            $file = Helper::createFileObject($path.$file_name);

            $test_file_name = time().'.'.$file->getClientOriginalExtension();

            $filePath='upload/'.$test_file_name;

            Storage::disk('s3')->put($filePath, file_get_contents($file));

            $disk = Storage::disk('s3');

            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                'Key'                        => $filePath,
                //'ResponseContentDisposition' => 'attachment;'//for download
            ]);

            $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

            $generate_url = $req->getUri();

            return '<img src='.$generate_url.' width="300px" height="300px">';
        }
    }

    public function reportStatusUpdate()
    {
        $business_id = Auth::user()->business_id;

        $candidate_id_arr = [
            751,923,446,674,676,680,989,1378,1462,934,
            936,1629,942,1588,1722,1707,3572,649,1781,1825,
            1930,1916,1926,2095,2098,2096,2097,2161,2162,2166,
            2257,2171,2164,2265,2262,2263,2264,1815,1611,2323,
            2289,2274,2036,1217,2476,2406,2435,2530,2550,2073,
            2850,2857,2867,2979,4719,3075,4093,3179,2892,2233,
            2596,3317,3223,3255,3316,3298,3299,3312,3329,2969,
            3323,3441,3469,3447,3437,3496,3485,3543,3543,3826,
            3827,3737,4723,3736,3499,4446,3184,4725,4724,3849,
            3703,3711,2167,2169,4447,3825,3817,3751,3822,3886,
            3906,1343,3871,3869,4691,3899,3910,4175,4171,4167,
            4168,4169,4170,4402,4277,4301,4401,4299,4406,4450,
            4452,3078,4409,3642,4405,4453,3183,4722,3819,3750,
            4726,3326,4496,4509,4508,4523,4547,4442,4501,4507,
            4532,4536,4510,4410,4568,4621,1934,4567,4573,4574,
            4859,4583,4577,4581,4625,4626,4627,4628,4629,4634,
            4630,4633,4636,4637,4638,4639,4643,4682,4686,4688,
            4689,4710,4379,4640,4641,4635,4644,4645,4646,4674,
            4664,4667,4670,4672,4675,4678,4647,4730,4445,3885,
            3595,4440,3877,4728,4729,4860,4861,4862,4863,4642,
            4717,4721,4727,4940,4947,4948,4949,4950,4951,4952,
            4953,4866,4904,4945,4913,4914,4915,4916,4917,4954,
            4886,4888,4890,4895,4896,4898,4899,4900,4901,4902,
            4905,4906,4907,4908,4909,4910,4911,4939,4941,4942,
            4943,4944,4884,4985,4986,4987,4988,5003,5005,5007,
            5009,5011,4989,4991,4956,4957,4958,4959,4960,4961,
            4962,4963,4964,4965,4966,4967,4968,4969,4970,4971,
            4972,4973,4974,4975,4979,4977,4978,5008,5012,5010,
            4955,4976,5002,4980,4981,4982,4983,4984,4990,4992,
            5001,5004,5006,4897,5015,5017,5027,5028,5030,5031,
            5034,5035,5014,5016,5018,5019,5020,5021,5022,5023,
            5024,5025,5033,5029,5036

        ];

        DB::table('reports')
                ->where(['parent_id'=>$business_id,'status'=>'incomplete'])
                ->whereDate('created_at','<=','2022-01-31')
                ->whereNotIn('candidate_id',$candidate_id_arr)
                ->update([
                    'status' => 'completed'
                ]);
    }
   
    public function assignTask(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $today_date = date('Y-m-d');
        $user_ids_arr=[];
        $users = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();
        $type = 'monthly';

        $to_date = date('Y-m-d');

        $from_date = date('Y-m-01');

        $customer_id=NULL;

        $month=[date('m',strtotime($today_date))];

        // $month=['01'];

        $year = '2022';
        if($request->get('type')!='')
        {
            if(stripos($request->type,'daily')!==false)
            {
                $from_date = date('Y-m-d');

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'weekly')!==false)
            {
                $from_date = date('Y-m-d',strtotime('- 6 days'));

                $to_date = date('Y-m-d');
            }
            else if(stripos($request->type,'monthly')!==false)
            {
                if($request->month!=null)
                {
                    $month = [];

                    $month = explode(',',$request->month);

                    if(count($month)>0)
                    {
                        sort($month);

                        $start = $month[0];

                        $end = end($month);

                        $year = $request->year;

                        $from_date = date('Y-m-d',strtotime($year.'-'.$start.'-'.'01'));

                        if($end==date('n') && $year==date('Y'))
                        {
                            $to_date = date('Y-m-d',strtotime($year.'-'.$end.'-'.date('d')));
                        }
                        else
                        {
                            $to_date = date('Y-m-t',strtotime($year.'-'.$end));
                        }
                    }
                    else{

                        $month=[date('n',strtotime($today_date))];

                        $to_date = date('Y-m-d');

                        $from_date = date('Y-m-01');

                    }

                }
            }

            $type = $request->type;

        }
        $month_str = implode(",",$month);

        $user_id_arr = DB::table('users')
                    ->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])
                    ->orderBy('name','asc')
                    ->pluck('id')->all();
        // array_unshift($user_id_arr,$business_id);

        if(count($user_id_arr)>0)
        {
            foreach($user_id_arr as $user_id)
            {
                $user = User::where(['id'=>$user_id])->first();
                // var_dump($users->name); die;
                // $a = $users->name;

                $ver_all_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                ->whereIn('ta.status',['1','2'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', t.assigned_to='.$user_id.')'); 
                // dd($ver_all_task); 
                if($request->get('from_date') !=""){
                    $ver_all_task->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $ver_all_task->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if(is_numeric($request->get('user_id'))){
                    $ver_all_task->where('ta.user_id',$request->get('user_id'));
                }
                // else if(stripos($type,'weekly')!==false)
                // {
                //     $ver_all_task->whereDate('t.start_date','>=',$from_date)->whereDate('t.start_date','<=',$to_date);
                // }
                // else if(stripos($type,'monthly')!==false)
                // {
                //     if($month!=NULL && count($month)>0)
                //     {
                //         $ver_all_task->whereIn(DB::raw('month(t.start_date)'),$month)->whereYear('t.start_date','=',$year);
                //     }
                //     else
                //     {
                //         $ver_all_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                //     }
                // }
                // else
                // {
                //     $ver_all_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                // }

                $ver_all_task=$ver_all_task->count();
                // $user_ids=$user_id.'-all';
                // dd($ver_all_task);
                // $user_id_arr[$user_ids]=$ver_all_task;
                // array_push($user_id_arr,count($ver_all_task));

                
                $ver_assign_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'1'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->get('from_date') !=""){
                    $ver_assign_task->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $ver_assign_task->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if(is_numeric($request->get('user_id'))){
                    $ver_assign_task->where('ta.user_id',$request->get('user_id'));
                }
                // else if(stripos($type,'weekly')!==false)
                // {
                //     $ver_assign_task->whereDate('t.start_date','>=',$from_date)->whereDate('t.start_date','<=',$to_date);
                // }
                // else if(stripos($type,'monthly')!==false)
                // {
                //     if($month!=NULL && count($month)>0)
                //     {
                //         $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),$month)->whereYear('t.start_date','=',$year);
                //     }
                //     else
                //     {
                //         $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                //     }
                // }
                // else
                // {
                //     $ver_assign_task->whereIn(DB::raw('month(t.start_date)'),[date('m')])->whereYear('t.start_date','=',date('Y'));
                // }

                $ver_assign_task=$ver_assign_task->count();
                // $user_ids=$user_id.'-assign';
                // $user_id_arr[$user_ids]=$ver_assign_task;
                // array_push($user_id_arr,count($ver_assign_task));
                // Verification Task Completed

                $ver_complete_task =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'t.description'=>'Task for Verification','ta.status'=>'2'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', ta.user_id='.$user_id.')');
                if($request->get('from_date') !=""){
                    $ver_complete_task->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $ver_complete_task->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if(is_numeric($request->get('user_id'))){
                    $ver_complete_task->where('ta.user_id',$request->get('user_id'));
                }
                // else if(stripos($type,'weekly')!==false)
                // {
                //     $ver_complete_task->whereDate('t.updated_at','>=',$from_date)->whereDate('t.updated_at','<=',$to_date);
                // }
                // else if(stripos($type,'monthly')!==false)
                // {
                //     if($month!=NULL && count($month)>0)
                //     {
                //         $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),$month)->whereYear('t.updated_at','=',$year);
                //     }
                //     else
                //     {
                //         $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
                //     }
                // }
                // else
                // {
                //     $ver_complete_task->whereIn(DB::raw('month(t.updated_at)'),[date('m')])->whereYear('t.updated_at','=',date('Y'));
                // }

                $ver_complete_task=$ver_complete_task->count();
                // $user_ids=$user_id.'-completed';
                // $user_id_arr[$user_ids]=$ver_complete_task;
                // dd(DB::getQueryLog());
                $user_ids_arr[$user_id] =['name'=>$user->name,'all'=>$ver_all_task,'pending'=>$ver_assign_task,'completed'=>$ver_complete_task];
            }
            // array_push($user_id_arr,$ver_complete_task);
           
            // dd($user_ids_arr);
        }
         if($request->ajax())
            return view('admin.accounts.task.ajax', compact('user_ids_arr','users','type'));
        else
            return view('admin.accounts.task.index', compact('user_ids_arr','users','type'));
      
    }

    public function fileAttachDeletion()
    {
        $business_id = Auth::user()->business_id;

        // Batch file Delete

        $batch_item_attach=DB::table('batch_item_attachements as bi')
                           ->select('bi.*')
                           ->join('batch_masters as b','b.id','=','bi.batch_id')
                           ->where('b.parent_id',$business_id)
                           ->where('bi.file_platform','web')
                           ->get();

        if(count($batch_item_attach)>0)
        {
            foreach($batch_item_attach as $item)
            {
                $path = public_path().'/uploads/batch-file/';
                if(File::exists($path.$item->file_name))
                    File::delete($path.$item->file_name);

                DB::table('batch_item_attachements')->where('id',$item->id)->delete();
            }
        }

        // Jaf File Delete

        $jaf_item_attach = DB::table('jaf_item_attachments')
                           ->where('business_id',$business_id)
                           ->where('file_platform','web')
                           ->get();

        if(count($jaf_item_attach)>0)
        {
          foreach($jaf_item_attach as $item)
          {
                $path = public_path().'/uploads/jaf-files/';
                if(File::exists($path.$item->file_name))
                   File::delete($path.$item->file_name);

                DB::table('jaf_item_attachments')->where('id',$item->id)->delete();
          }
        }

        // Report File Delete

        $report_item_attach = DB::table('report_item_attachments as ria')
                              ->select('ria.*')
                              ->join('reports as r','r.id','=','ria.report_id')
                              ->where('r.parent_id',$business_id)
                              ->where('ria.file_platform','web')
                              ->get();

        if(count($report_item_attach)>0)
        {
          foreach($report_item_attach as $item)
          {
                $path = public_path().'/uploads/report-files/';
                if(File::exists($path.$item->file_name))
                {
                    File::delete($path.$item->file_name);
                }

                DB::table('jaf_item_attachments')->where('id',$item->id)->delete();
          }
        }

        echo 'done';

    }

    public function testNotification(){
        
        $notification_controls = DB::table('notification_control_configs as nc')
            ->select('nc.*')
            ->join('notification_controls as n','n.business_id','=','nc.business_id')
            ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>98,'n.type'=>'jaf-sent-to-candidate','nc.type'=>'jaf-sent-to-candidate'])
            ->toSql();

        dd($notification_controls);
    }

    public function testValidateRekrut()
    {
        $business_id = 98;
        $client_rekrut = DB::table('users as u')
        ->distinct('cs.service_id')
        ->select('u.*','cs.service_id')
        ->where('u.id',98)
        ->join('customer_sla as c','c.business_id','=','u.id')
        ->join('customer_sla_items as cs','cs.sla_id','=','c.id')
        ->where(['u.user_type'=>'client'])
        ->where('c.id',58)
        // ->whereNotIn('cs.service_id',[17])
        ->groupBy('cs.service_id')
        ->get();

        if($business_id=98 && count($client_rekrut)==2 && $client_rekrut->contains('service_id',17))
            dd($client_rekrut);
    }

    /*
        Function use for changing the task status to pending
    */

    public function updateTaskStatus()
    {
        $path=public_path().'/task-reference-list/';

        $file_name = 'ReferenceNo.xlsx';

        $file = Helper::createFileObject($path.$file_name);

        $parsed_array = Excel::toArray([], $file);

        $imported_data = array_splice($parsed_array[0], 1);

        //dd($imported_data);
        $i=0;
        foreach($imported_data as $value)
        {
            $display_id = $value[0];

            $user=DB::table('users')->where('display_id',$display_id)->where('user_type','candidate')->first();

            if($user!=NULL)
            {
                $job_item = DB::table('job_items')->where('candidate_id',$user->id)->where('is_jaf_ready_report',0)->first();

                if($job_item!=NULL)
                {
                    $tasks = DB::table('task_assignments as ta')
                    ->select('ta.*')
                    ->join('tasks as t','t.id','=','ta.task_id')
                    ->where(['ta.candidate_id'=>$user->id,'ta.status'=>'2','t.description'=>'Task for Verification '])
                    ->get();

                    if(count($tasks)>0)
                    {
                        foreach($tasks as $task)
                        {
                            DB::table('task_assignments')->where('id',$task->id)->update([
                                'status' => '1'
                            ]);
                        }

                        $i++;
                    }
                }
            }
        }

        echo 'done '.$i;
    }

    // This url is used for staging or live server to update the record for s3

    public function updateS3Attach()
    {
        $path = public_path()."/uploads/report-files/";

        $report_attachments=DB::table('report_item_attachments')->where('file_platform','web')->get();

        if(count($report_attachments)>0)
        {
            $i=0;
            foreach($report_attachments as $attach)
            {
                if(File::exists($path.$attach->file_name))
                {
                    DB::table('report_item_attachments')->where('id',$attach->id)->update([
                        'file_platform'  => 's3'
                    ]);

                    File::delete($path.$attach->file_name);

                    $i++;
                }
            }

            echo 'report attachment - done '.$i;
        }

        $path = public_path()."/uploads/jaf-files/";

        $jaf_attachments=DB::table('jaf_item_attachments')->where('file_platform','web')->get();

        if(count($jaf_attachments)>0)
        {
            $i=0;
            foreach($jaf_attachments as $attach)
            {
                if(File::exists($path.$attach->file_name))
                {
                    DB::table('jaf_item_attachments')->where('id',$attach->id)->update([
                        'file_platform'  => 's3'
                    ]);

                    File::delete($path.$attach->file_name);

                    $i++;
                }
            }

            echo 'jaf attachment - done '.$i;
        }

        $path = public_path()."/uploads/jaf_details/";

        $jaf_attachments=DB::table('jaf_files')->where('file_platform','web')->get();

        if(count($jaf_attachments)>0)
        {
            $i=0;
            foreach($jaf_attachments as $attach)
            {
                if(File::exists($path.$attach->file_name))
                {
                    DB::table('jaf_files')->where('id',$attach->id)->update([
                        'file_platform'  => 's3'
                    ]);

                    File::delete($path.$attach->file_name);

                    $i++;
                }
            }

            echo 'jaf files - done '.$i;
        }
    }
    
    public function checkControllerCOC()
    {
        $customers= DB::table('users')->where(['user_type'=>'client','status'=>'1'])->get();
        if (count($customers)>0) {
            foreach ($customers as $customer) {
                $data=[
                    'parent_id'=>$customer->parent_id,
                    'business_id'=>$customer->business_id,
                    'coc_id'=>$customer->id,
                    'shown_by'=>$customer->parent_id,
                    'shown_at'=>date('Y-m-d H:i:s'),
                ];
                DB::table('check_coc_controls')->insert($data);
            }
           
        }
    }

    public function slaDisplayId()
    {
        $customer_sla = DB::table('customer_sla as cs')
                            ->select('cs.*','ub.company_name','ub.company_short_name')
                            ->join('user_businesses as ub','cs.business_id','=','ub.business_id')
                            ->get();
        if(count($customer_sla)>0)
        {
            foreach($customer_sla as $sla)
            {
                $sla_id = str_pad($sla->id, 10, "0", STR_PAD_LEFT);
                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($sla->company_name,0,4)))).'-'.trim(strtoupper(substr($sla->title,0,4))).'-'.$sla_id;

                DB::table('customer_sla')->where('id',$sla->id)->update([
                    'display_id' => $display_id 
                ]);
            }
            echo 'done';
        }
        
        
       
        
    }

    public function userPasswordUpdate()
    {
        $email = 'pradnya.gondikar@fortunaidentity.com';

        $password = 'Pradnya@123';

        // hash password
        $pass = bcrypt($password);

        DB::table('users')->where('email',$email)->update(['password'=>$pass]);
    }

    public function candidateReinitateTransfer()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '500M');
        $candidates = DB::table('users')->where('user_type','candidate')->get();

        if(count($candidates)>0)
        {
            foreach($candidates as $item)
            {
                $data=DB::table('candidate_reinitiates')->where('id',$item->id)->first();

                if($data!=NULL)
                {
                    DB::table('candidate_reinitiates')->where('id',$item->id)->update([
                        'display_id' => $item->display_id,
                        'client_emp_code' => $item->client_emp_code,
                        'entity_code' => $item->entity_code,
                        'entity_code' => $item->entity_code,
                        'user_type' => 'candidate',
                        'role' => $item->role,
                        'user_account_type' => $item->user_account_type,
                        'business_id' => $item->business_id,
                        'parent_id' => $item->parent_id,
                        'name' => $item->name,
                        'first_name' => $item->first_name,
                        'middle_name' => $item->middle_name,
                        'last_name' => $item->last_name,
                        'father_name' => $item->father_name,
                        'dob' => $item->dob,
                        'gender' => $item->gender,
                        'email' => $item->email,
                        'alternative_email' => $item->alternative_email,
                        'is_email_verified' => $item->is_email_verified,
                        'email_verified_at' => $item->email_verified_at,
                        'email_verification_sent_at' => $item->email_verification_sent_at,
                        'email_verification_resend_at' => $item->email_verification_resend_at,
                        'email_verification_token' => $item->email_verification_token,
                        'password' => $item->password,
                        'remember_token' => $item->remember_token,
                        'session_id' => $item->session_id,
                        'api_token' => $item->api_token,
                        'phone' => $item->phone,
                        'phone_iso' => $item->phone_iso,
                        'phone_code' => $item->phone_code,
                        'profile_image' => $item->profile_image,
                        'company_logo' => $item->company_logo,
                        'company_logo_file_platform' => $item->company_logo_file_platform,
                        'report_company_name' => $item->report_company_name,
                        'digital_signature' => $item->digital_signature,
                        'digital_signature_file_platform' => $item->digital_signature_file_platform,
                        'timezone_id' => $item->timezone_id,
                        'timezone_value' => $item->timezone_value,
                        'country_id' => $item->country_id,
                        'is_logged_in' => $item->is_logged_in,
                        'device_type' => $item->device_type,
                        'ios_token' => $item->ios_token,
                        'android_token' => $item->android_token,
                        'sms_otp' => $item->sms_otp,
                        'sms_otp_sent_at' => $item->sms_otp_sent_at,
                        'is_sms_otp_verified' => $item->is_sms_otp_verified,
                        'sms_otp_verified_at' => $item->sms_otp_verified_at,
                        'status' => $item->status,
                        'is_blocked' => $item->is_blocked,
                        'blocked_at' => $item->blocked_at,
                        'unblocked_at' => $item->unblocked_at,
                        'attempts' => $item->attempts,
                        'customer_type' => $item->customer_type,
                        'is_business_data_completed' => $item->is_business_data_completed,
                        'created_source' => $item->created_source,
                        'priority' => $item->priority,
                        'aadhar_number' => $item->aadhar_number,
                        'address' => $item->address,
                        'address_type' => $item->address_type,
                        'coc_revenue_category' => $item->coc_revenue_category,
                        'is_deleted' => $item->is_deleted,
                        'deleted_at' => $item->deleted_at,
                        'is_user_logged' => $item->is_user_logged,
                        'last_logindate_utc' => $item->last_logindate_utc,
                        'token_updated_at' => $item->token_updated_at,
                        'case_received_date' => $item->case_received_date,
                        'platform_reference' => $item->platform_reference,
                        'is_purged' => $item->is_purged,
                        'purge_days' => $item->purge_days,
                        'purge_date' => $item->purge_date,
                        'is_export_notify' => $item->is_export_notify,
                        'export_notify_by' => $item->export_notify_by,
                        'export_notify_at' => $item->export_notify_at,
                        'is_notify' => $item->is_notify,
                        'notify_by' => $item->notify_by,
                        'notify_at' => $item->notify_at,
                        'frnid' => $item->frnid,
                        'recruiter' => $item->recruiter,
                        'source' => $item->source,
                        'cost_code' => $item->cost_code,
                        'grade' => $item->grade,
                        'grade' => $item->grade,
                        'deleted_by' => $item->deleted_by,
                        'created_by' => $item->created_by,
                        'updated_by' => $item->updated_by,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ]);
                }
                else
                {
                    DB::table('candidate_reinitiates')->insert([
                        'id' => $item->id,
                        'display_id' => $item->display_id,
                        'client_emp_code' => $item->client_emp_code,
                        'entity_code' => $item->entity_code,
                        'entity_code' => $item->entity_code,
                        'user_type' => 'candidate',
                        'role' => $item->role,
                        'user_account_type' => $item->user_account_type,
                        'business_id' => $item->business_id,
                        'parent_id' => $item->parent_id,
                        'name' => $item->name,
                        'first_name' => $item->first_name,
                        'middle_name' => $item->middle_name,
                        'last_name' => $item->last_name,
                        'father_name' => $item->father_name,
                        'dob' => $item->dob,
                        'gender' => $item->gender,
                        'email' => $item->email,
                        'alternative_email' => $item->alternative_email,
                        'is_email_verified' => $item->is_email_verified,
                        'email_verified_at' => $item->email_verified_at,
                        'email_verification_sent_at' => $item->email_verification_sent_at,
                        'email_verification_resend_at' => $item->email_verification_resend_at,
                        'email_verification_token' => $item->email_verification_token,
                        'password' => $item->password,
                        'remember_token' => $item->remember_token,
                        'session_id' => $item->session_id,
                        'api_token' => $item->api_token,
                        'phone' => $item->phone,
                        'phone_iso' => $item->phone_iso,
                        'phone_code' => $item->phone_code,
                        'profile_image' => $item->profile_image,
                        'company_logo' => $item->company_logo,
                        'company_logo_file_platform' => $item->company_logo_file_platform,
                        'report_company_name' => $item->report_company_name,
                        'digital_signature' => $item->digital_signature,
                        'digital_signature_file_platform' => $item->digital_signature_file_platform,
                        'timezone_id' => $item->timezone_id,
                        'timezone_value' => $item->timezone_value,
                        'country_id' => $item->country_id,
                        'is_logged_in' => $item->is_logged_in,
                        'device_type' => $item->device_type,
                        'ios_token' => $item->ios_token,
                        'android_token' => $item->android_token,
                        'sms_otp' => $item->sms_otp,
                        'sms_otp_sent_at' => $item->sms_otp_sent_at,
                        'is_sms_otp_verified' => $item->is_sms_otp_verified,
                        'sms_otp_verified_at' => $item->sms_otp_verified_at,
                        'status' => $item->status,
                        'is_blocked' => $item->is_blocked,
                        'blocked_at' => $item->blocked_at,
                        'unblocked_at' => $item->unblocked_at,
                        'attempts' => $item->attempts,
                        'customer_type' => $item->customer_type,
                        'is_business_data_completed' => $item->is_business_data_completed,
                        'created_source' => $item->created_source,
                        'priority' => $item->priority,
                        'aadhar_number' => $item->aadhar_number,
                        'address' => $item->address,
                        'address_type' => $item->address_type,
                        'coc_revenue_category' => $item->coc_revenue_category,
                        'is_deleted' => $item->is_deleted,
                        'deleted_at' => $item->deleted_at,
                        'is_user_logged' => $item->is_user_logged,
                        'last_logindate_utc' => $item->last_logindate_utc,
                        'token_updated_at' => $item->token_updated_at,
                        'case_received_date' => $item->case_received_date,
                        'platform_reference' => $item->platform_reference,
                        'is_purged' => $item->is_purged,
                        'purge_days' => $item->purge_days,
                        'purge_date' => $item->purge_date,
                        'is_export_notify' => $item->is_export_notify,
                        'export_notify_by' => $item->export_notify_by,
                        'export_notify_at' => $item->export_notify_at,
                        'is_notify' => $item->is_notify,
                        'notify_by' => $item->notify_by,
                        'notify_at' => $item->notify_at,
                        'frnid' => $item->frnid,
                        'recruiter' => $item->recruiter,
                        'source' => $item->source,
                        'cost_code' => $item->cost_code,
                        'grade' => $item->grade,
                        'grade' => $item->grade,
                        'deleted_by' => $item->deleted_by,
                        'created_by' => $item->created_by,
                        'updated_by' => $item->updated_by,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ]);
                }
            }
            
            dd('candidate records: '.count($candidates));
        }
    }

    public function candidateDeletion()
    {
        $business_id = Auth::user()->business_id;

        DB::beginTransaction();
        try{
          if(Auth::check()){

            $modules = [];

            $tables = [];

            $module_all = [];

            // DB::table('candidate_reinitiates')
            //   ->where('id', $candidate_id)
            //   ->update(['is_deleted' => 1,'deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);

            $users = CandidateReinitiate::where('parent_id',$business_id)->first();

            if(count($users)>0)
            {

                foreach($users as $user)
                {
                    $candidate_id = $user->id;
                    // Check Tables

                    $aadhar_check=DB::table('aadhar_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($aadhar_check)>0)
                    {
                        $tables[]='aadhar_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'aadhar_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['aadhar_checks'];
                        }

                        foreach($aadhar_check as $aadhar)
                        {
                            AadharCheck::find($aadhar->id)->delete();
                        }
                    }

                    $bank_check=DB::table('bank_account_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($bank_check)>0)
                    {
                        $tables[]='bank_account_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'bank_account_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['bank_account_checks'];
                        }

                        foreach($bank_check as $bank)
                        {
                            BankAccountCheck::find($bank->id)->delete();
                        }
                    }

                    $cin_check=DB::table('cin_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($cin_check)>0)
                    {
                        $tables[]='cin_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'cin_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['cin_checks'];
                        }

                        foreach($cin_check as $cin)
                        {
                            CinCheck::find($cin->id)->delete();
                        }
                    }

                    $covid19_check=DB::table('covid19_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($covid19_check)>0)
                    {
                        $tables[]='covid19_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'covid19_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['covid19_checks'];
                        }

                        foreach($covid19_check as $covid)
                        {
                            Covid19Check::find($covid->id)->delete();
                        }
                    }

                    $dl_check=DB::table('dl_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($dl_check)>0)
                    {
                        $tables[]='dl_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'dl_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['dl_checks'];
                        }

                        foreach($dl_check as $dl)
                        {
                            DlCheck::find($dl->id)->delete();
                        }
                    }

                    $ecourt_check=DB::table('e_court_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($ecourt_check)>0)
                    {
                        $tables[]='e_court_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'e_court_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['e_court_checks'];
                        }

                        foreach($ecourt_check as $ecourt)
                        {
                            ECourtCheck::find($ecourt->id)->delete();
                        }
                    }

                    $ecourt_check_items=DB::table('e_court_check_items')->where('candidate_id',$candidate_id)->get();

                    if(count($ecourt_check_items)>0)
                    {
                        $tables[]='e_court_check_items';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'e_court_check_items');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['e_court_check_items'];
                        }

                        foreach($ecourt_check_items as $ecourt)
                        {
                            ECourtCheckItem::find($ecourt->id)->delete();
                        }
                    }

                    $gst_check=DB::table('gst_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($gst_check)>0)
                    {
                        $tables[]='gst_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'gst_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['gst_checks'];
                        }

                        foreach($gst_check as $gst)
                        {
                            GstCheck::find($gst->id)->delete();
                        }
                    }

                    $pan_check=DB::table('pan_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($pan_check)>0)
                    {
                        $tables[]='pan_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'pan_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['pan_checks'];
                        }

                        foreach($pan_check as $pan)
                        {
                            PanCheck::find($pan->id)->delete();
                        }
                    }

                    $passport_check=DB::table('passport_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($passport_check)>0)
                    {
                        $tables[]='passport_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'passport_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['passport_checks'];
                        }

                        foreach($passport_check as $passport)
                        {
                            PassportCheck::find($passport->id)->delete();
                        }
                    }

                    $rc_check=DB::table('rc_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($rc_check)>0)
                    {
                        $tables[]='rc_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'rc_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['rc_checks'];
                        }

                        foreach($rc_check as $rc)
                        {
                            RcCheck::find($rc->id)->delete();
                        }
                    }      // Check Tables

                    $aadhar_check=DB::table('aadhar_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($aadhar_check)>0)
                    {
                        $tables[]='aadhar_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'aadhar_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['aadhar_checks'];
                        }

                        foreach($aadhar_check as $aadhar)
                        {
                            AadharCheck::find($aadhar->id)->delete();
                        }
                    }

                    $bank_check=DB::table('bank_account_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($bank_check)>0)
                    {
                        $tables[]='bank_account_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'bank_account_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['bank_account_checks'];
                        }

                        foreach($bank_check as $bank)
                        {
                            BankAccountCheck::find($bank->id)->delete();
                        }
                    }

                    $cin_check=DB::table('cin_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($cin_check)>0)
                    {
                        $tables[]='cin_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'cin_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['cin_checks'];
                        }

                        foreach($cin_check as $cin)
                        {
                            CinCheck::find($cin->id)->delete();
                        }
                    }

                    $covid19_check=DB::table('covid19_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($covid19_check)>0)
                    {
                        $tables[]='covid19_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'covid19_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['covid19_checks'];
                        }

                        foreach($covid19_check as $covid)
                        {
                            Covid19Check::find($covid->id)->delete();
                        }
                    }

                    $dl_check=DB::table('dl_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($dl_check)>0)
                    {
                        $tables[]='dl_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'dl_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['dl_checks'];
                        }

                        foreach($dl_check as $dl)
                        {
                            DlCheck::find($dl->id)->delete();
                        }
                    }

                    $ecourt_check=DB::table('e_court_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($ecourt_check)>0)
                    {
                        $tables[]='e_court_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'e_court_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['e_court_checks'];
                        }

                        foreach($ecourt_check as $ecourt)
                        {
                            ECourtCheck::find($ecourt->id)->delete();
                        }
                    }

                    $ecourt_check_items=DB::table('e_court_check_items')->where('candidate_id',$candidate_id)->get();

                    if(count($ecourt_check_items)>0)
                    {
                        $tables[]='e_court_check_items';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'e_court_check_items');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['e_court_check_items'];
                        }

                        foreach($ecourt_check_items as $ecourt)
                        {
                            ECourtCheckItem::find($ecourt->id)->delete();
                        }
                    }

                    $gst_check=DB::table('gst_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($gst_check)>0)
                    {
                        $tables[]='gst_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'gst_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['gst_checks'];
                        }

                        foreach($gst_check as $gst)
                        {
                            GstCheck::find($gst->id)->delete();
                        }
                    }

                    $pan_check=DB::table('pan_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($pan_check)>0)
                    {
                        $tables[]='pan_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'pan_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['pan_checks'];
                        }

                        foreach($pan_check as $pan)
                        {
                            PanCheck::find($pan->id)->delete();
                        }
                    }
                    $passport_check=DB::table('passport_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($passport_check)>0)
                    {
                        $tables[]='passport_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'passport_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['passport_checks'];
                        }

                        foreach($passport_check as $passport)
                        {
                            PassportCheck::find($passport->id)->delete();
                        }
                    }

                    $rc_check=DB::table('rc_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($rc_check)>0)
                    {
                        $tables[]='rc_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'rc_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['rc_checks'];
                        }

                        foreach($rc_check as $rc)
                        {
                            RcCheck::find($rc->id)->delete();
                        }
                    }

                    $telecom_check=DB::table('telecom_check')->where('candidate_id',$candidate_id)->get();

                    if(count($telecom_check)>0)
                    {
                        $tables[]='telecom_check';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'telecom_check');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['telecom_check'];
                        }

                        foreach($telecom_check as $telecom)
                        {
                            TelecomCheck::find($telecom->id)->delete();
                        }
                    }

                    $upi_check=DB::table('upi_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($upi_check)>0)
                    {
                        $tables[]='upi_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'upi_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['upi_checks'];
                        }

                        foreach($upi_check as $upi)
                        {
                            UpiCheck::find($upi->id)->delete();
                        }
                    }

                    $voter_id=DB::table('voter_id_checks')->where('candidate_id',$candidate_id)->get();

                    if(count($voter_id)>0)
                    {
                        $tables[]='voter_id_checks';

                        if(array_key_exists('Verification Checks',$module_all))
                        {
                            array_push($module_all['Verification Checks'],'voter_id_checks');
                        }
                        else
                        {
                            $module_all['Verification Checks']=['voter_id_checks'];
                        }

                        foreach($voter_id as $voter)
                        {
                            VoterIdCheck::find($voter->id)->delete();
                        }
                    }

                    if(count($aadhar_check)>0 || count($pan_check)>0 || count($voter_id)>0 || count($rc_check)>0 || count($dl_check)>0 || count($passport_check)>0 || count($bank_check)>0 || count($covid19_check)>0 || count($ecourt_check)>0 || count($ecourt_check_items) > 0 || count($cin_check)>0 || count($upi_check)>0 || count($gst_check)>0)
                    {
                    $modules[]='Verification Checks';
                    }

                    // 

                    $candidates=DB::table('candidates')->where('candidate_id',$candidate_id)->get();

                    $modules[]='Candidate';

                    if(count($candidates)>0)
                    {
                        $tables[]='candidates';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'candidates');
                        }
                        else
                        {
                            $module_all['Candidate']=['candidates'];
                        }

                        foreach($candidates as $c)
                        {
                            Candidate::find($c->id)->delete();
                        }
                    }


                    $candidate_hold=DB::table('candidate_hold_statuses')->where('candidate_id',$candidate_id)->get();

                    if(count($candidate_hold)>0)
                    {
                        $tables[]='candidate_hold_statuses';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'candidate_hold_statuses');
                        }
                        else
                        {
                            $module_all['Candidate']=['candidate_hold_statuses'];
                        }

                        foreach($candidate_hold as $c)
                        {
                        CandidateHoldStatus::find($c->id)->delete();
                        }
                    }

                    $candidate_jaf_status = DB::table('candidate_jaf_status')->where('candidate_id',$candidate_id)->get();

                    if(count($candidate_jaf_status)>0)
                    {
                        DB::table('candidate_jaf_status')->where('candidate_id',$candidate_id)->delete();

                        $tables[]='candidate_jaf_status';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'candidate_jaf_status');
                        }
                        else
                        {
                            $module_all['Candidate']=['candidate_jaf_status'];
                        }
                    }
                    

                    $coc_notify=DB::table('coc_notification_masters')->where('candidate_id',$candidate_id)->get();

                    if(count($coc_notify)>0)
                    {
                        $tables[]='coc_notification_masters';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'coc_notification_masters');
                        }
                        else
                        {
                            $module_all['Candidate']=['coc_notification_masters'];
                        }

                        foreach($coc_notify as $c)
                        {
                        CocNotificationMaster::find($c->id)->delete();
                        }
                    }

                    $digital_address=DB::table('digital_address_verifications')->where('candidate_id',$candidate_id)->get();

                    if(count($digital_address)>0)
                    {
                        $tables[]='digital_address_verifications';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'digital_address_verifications');
                        }
                        else
                        {
                            $module_all['Candidate']=['digital_address_verifications'];
                        }

                        foreach($digital_address as $digital)
                        {
                        DigitalAddressVerification::find($digital->id)->delete();
                        }
                    }

                    $report_exports = DB::table('report_exports')->where('candidate_id',$candidate_id)->get();

                    if(count($report_exports)>0)
                    {
                        $tables[]='report_exports';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'report_exports');
                        }
                        else
                        {
                            $module_all['Candidate']=['report_exports'];
                        }

                        DB::table('report_exports')->where('candidate_id',$candidate_id)->delete();
                    }

                    $verification_insuff=DB::table('verification_insufficiency')->where('candidate_id',$candidate_id)->get();

                    if(count($verification_insuff)>0)
                    {
                        $tables[]='verification_insufficiency';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'verification_insufficiency');
                        }
                        else
                        {
                            $module_all['Candidate']=['verification_insufficiency'];
                        }

                        foreach($verification_insuff as $insuff)
                        {
                        VerificationInsufficiency::find($insuff->id)->delete();
                        }
                    }

                    $address_verifications=DB::table('address_verifications')->where('candidate_id',$candidate_id)->get();

                    if(count($address_verifications)>0)
                    {
                        $tables[]='address_verifications';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'address_verifications');
                        }
                        else
                        {
                            $module_all['Candidate']=['address_verifications'];
                        }

                        foreach($address_verifications as $address)
                        {
                        AddressVerification::find($address->id)->delete();
                        }
                    }

                    $address_decision=DB::table('address_verification_decision_logs')->where('candidate_id',$candidate_id)->get();

                    if(count($address_decision)>0)
                    {
                        $tables[]='address_verification_decision_logs';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'address_verification_decision_logs');
                        }
                        else
                        {
                            $module_all['Candidate']=['address_verification_decision_logs'];
                        }

                        foreach($address_decision as $address)
                        {
                        AddressVerificationDecisionLog::find($address->id)->delete();
                        }
                    }

                    $otp_email=DB::table('otp_by_email')->where('candidate_id',$candidate_id)->get();

                    if(count($otp_email)>0)
                    {
                        $tables[]='otp_by_email';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'otp_by_email');
                        }
                        else
                        {
                            $module_all['Candidate']=['otp_by_email'];
                        }

                        foreach($otp_email as $otp)
                        {
                        OtpByEmail::find($otp->id)->delete();
                        }
                    }


                    // $jobs=DB::table('jobs')->where('candidate_id',$candidate_id)->get();

                    // if(count($jobs)>0)
                    // {
                    //     foreach($jobs as $job)
                    //     {
                    //       Job::find($job->id)->delete();
                    //     }
                    // }

                    $job_items=DB::table('job_items')->where('candidate_id',$candidate_id)->get();

                    if(count($job_items)>0)
                    {
                        $tables[]='job_items';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'job_items');
                        }
                        else
                        {
                            $module_all['Candidate']=['job_items'];
                        }

                        $tables[]='jobs';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'jobs');
                        }
                        else
                        {
                            $module_all['Candidate']=['jobs'];
                        }

                        foreach($job_items as $job)
                        {
                        JobItem::find($job->id)->delete();

                        Job::find($job->job_id)->delete();
                        }
                    }

                    $job_sla_items=DB::table('job_sla_items')->where('candidate_id',$candidate_id)->get();

                    if(count($job_sla_items)>0)
                    {
                        $tables[]='job_sla_items';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'job_sla_items');
                        }
                        else
                        {
                            $module_all['Candidate']=['job_sla_items'];
                        }

                        foreach($job_sla_items as $job)
                        {
                        JobSlaItem::find($job->id)->delete();
                        }
                    }

                    $tasks=DB::table('tasks')->where('candidate_id',$candidate_id)->get();

                    if(count($tasks)>0)
                    {
                        $modules[]='Task';

                        $tables[]='tasks';

                        if(array_key_exists('Task',$module_all))
                        {
                            array_push($module_all['Task'],'tasks');
                        }
                        else
                        {
                            $module_all['Task']=['tasks'];
                        }

                        foreach($tasks as $task)
                        {
                        Task::find($task->id)->delete();
                        }
                    }

                    $tasks=DB::table('task_assignments')->where('candidate_id',$candidate_id)->get();

                    if(count($tasks)>0)
                    {
                        $tables[]='task_assignments';

                        if(array_key_exists('Task',$module_all))
                        {
                            array_push($module_all['Task'],'task_assignments');
                        }
                        else
                        {
                            $module_all['Task']=['task_assignments'];
                        }

                        foreach($tasks as $task)
                        {
                        TaskAssignment::find($task->id)->delete();
                        }
                    }

                    $insufficiency_attach = DB::table('insufficiency_attachments')->where('candidate_id',$candidate_id)->get();

                    if(count($insufficiency_attach)>0)
                    {
                        $tables[]='insufficiency_attachments';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'insufficiency_attachments');
                        }
                        else
                        {
                            $module_all['Candidate']=['insufficiency_attachments'];
                        }

                        foreach($insufficiency_attach as $insuff)
                        {

                        if($insuff->file_platform=='web')
                        {
                            $path = public_path('/uploads/raise-insuff/');

                            if(File::exists($path.$insuff->file_name))
                            {
                                File::delete($path.$insuff->file_name);
                            }

                            $path = public_path('/uploads/clear-insuff/');

                            if(File::exists($path.$insuff->file_name))
                            {
                                File::delete($path.$insuff->file_name);
                            }
                        }

                        DB::table('insufficiency_attachments')->where('id',$insuff->id)->delete();

                        }
                    }

                    $jaf_files = DB::table('jaf_files')->where('candidate_id',$candidate_id)->get();

                    if(count($jaf_files)>0)
                    {
                        $tables[]='jaf_files';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'jaf_files');
                        }
                        else
                        {
                            $module_all['Candidate']=['jaf_files'];
                        }

                        foreach($jaf_files as $file)
                        {
                        $path = public_path('/uploads/jaf_details/');

                        if(File::exists($path.$file->file_name))
                        {
                            File::delete($path.$file->file_name);
                        }

                        DB::table('jaf_files')->where('id',$file->id)->delete();
                        }
                    }

                    $jaf_form_data=DB::table('jaf_form_data')->where('candidate_id',$candidate_id)->get();

                    if(count($jaf_form_data)>0)
                    {
                        $tables[]='jaf_form_data';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'jaf_form_data');
                        }
                        else
                        {
                            $module_all['Candidate']=['jaf_form_data'];
                        }

                        foreach($jaf_form_data as $key => $jaf)
                        {
                        $jaf_additional_charges = DB::table('jaf_additional_charge_attachments')->where('jaf_id',$jaf->id)->get();

                        if(count($jaf_additional_charges)>0)
                        {
                            if($key==0)
                            {
                                $tables[]='jaf_additional_charge_attachments';

                                if(array_key_exists('Candidate',$module_all))
                                {
                                    array_push($module_all['Candidate'],'jaf_additional_charge_attachments');
                                }
                                else
                                {
                                    $module_all['Candidate']=['jaf_additional_charge_attachments'];
                                }
                            }

                            foreach($jaf_additional_charges as $attach)
                            {
                                $path = public_path('/uploads/jaf/additional-charge/');

                                if(File::exists($path.$attach->file_name))
                                {
                                File::delete($path.$attach->file_name);
                                }

                                DB::table('jaf_additional_charge_attachments')->where('id',$attach->id)->delete();

                            }
                        }

                        JafFormData::find($jaf->id)->delete();
                        }
                    }

                    $jaf_attach=DB::table('jaf_item_attachments')->where('candidate_id',$candidate_id)->get();

                    if(count($jaf_attach)>0)
                    {
                        $tables[]='jaf_item_attachments';

                        if(array_key_exists('Candidate',$module_all))
                        {
                            array_push($module_all['Candidate'],'jaf_item_attachments');
                        }
                        else
                        {
                            $module_all['Candidate']=['jaf_item_attachments'];
                        }

                        foreach($jaf_attach as $attach)
                        {
                        $path = public_path('/uploads/jaf-files/');

                        if(File::exists($path.$attach->file_name))
                        {
                            File::delete($path.$attach->file_name);
                        }

                        DB::table('jaf_item_attachments')->where('id',$attach->id)->delete();
                        }
                    }

                    $reports=DB::table('reports')->where('candidate_id',$candidate_id)->get();

                    if(count($reports)>0)
                    {
                        $modules[]='Report';

                        $tables[]='reports';

                        if(array_key_exists('Report',$module_all))
                        {
                            array_push($module_all['Report'],'reports');
                        }
                        else
                        {
                            $module_all['Report']=['reports'];
                        }

                        foreach($reports as $report)
                        {
                        Report::find($report->id)->delete();
                        }
                    }

                    $report_items=DB::table('report_items')->where('candidate_id',$candidate_id)->get();

                    if(count($report_items)>0)
                    {
                        $tables[]='report_items';

                        if(array_key_exists('Report',$module_all))
                        {
                            array_push($module_all['Report'],'report_items');
                        }
                        else
                        {
                            $module_all['Report']=['report_items'];
                        }

                        foreach($report_items as $key => $report)
                        {

                        $report_attach=DB::table('report_item_attachments')->where('report_item_id',$report->id)->get();

                        if(count($report_attach)>0)
                        {
                            if($key==0)
                            {
                                $tables[]='report_item_attachments';

                                if(array_key_exists('Report',$module_all))
                                {
                                    array_push($module_all['Report'],'report_item_attachments');
                                }
                                else
                                {
                                    $module_all['Report']=['report_item_attachments'];
                                }
                            }

                            foreach($report_attach as $attach)
                            {
                                $path = public_path('/uploads/report-files/');

                                if(File::exists($path.$attach->file_name))
                                {
                                File::delete($path.$attach->file_name);
                                }

                                DB::table('report_item_attachments')->where('id',$attach->id)->delete();
                            }
                        }

                        ReportItem::find($report->id)->delete();
                        }
                    }

                    $tables[]='users';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'users');
                    }
                    else
                    {
                        $module_all['Candidate']=['users'];
                    }

                    CandidateReinitiate::find($candidate_id)->delete();

                    $is_deleted=TRUE;
                }
                
                //return result 
                if($is_deleted){   
                    DB::commit();
                    return response()->json([
                    'status'=>'ok',
                    'message' => 'deleted',                
                    ], 200);
                }
                else{
                    return response()->json([
                    'status' =>'no',
                    ], 200);
                }

            }

          }
          else{   
              return response()->json([
                  'status' =>'no',
                  ], 200);
          }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    public function guestPayReport()
    {
       $file_name = 'pay-report'.date('YmdHis').'.xlsx';

       return Excel::download(new GuestPayReport(),$file_name);
    }


    public function frnidIdmport(Request $readonly)
    {
        return view('admin.frnid');
    }

    public function excelfileimport(Request $request)
    {
    
      $file = $request->excel_file;
      $parsed_array = Excel::toArray([], $file);

      $imported_data = array_splice($parsed_array[1], 1);

     //dd(count($imported_data));
      if(count($imported_data)>0)
      {
          $i=0;
          foreach ($imported_data as $value)
          {
            $user_data = 
                  [
                    'frnid' => $value[0],
                  ];

                  $display_id = $value[2];
          
              $disp_id = CandidateReinitiate::where(['display_id'=>$display_id])->first();

              if($disp_id != null)
              {
                  CandidateReinitiate::where(['display_id'=>$display_id])->update([
                      'frnid' => $value[0],
                  ]);
                  $i++;
              }
          }

        dd('candidate frnid count: '.$i);
      }
    }
      
    public function reportItemCreate(Request $request)
    {
        $parent_id=Auth::user()->parent_id;

        $candidate_ids = $request->id!=null ? explode(',',$request->id) : [];

        if(count($candidate_ids)>0)
        {
            foreach($candidate_ids as $candidate_id)
            {
                $report = DB::table('reports')->where('candidate_id',$candidate_id)->first();

                $job = DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first(); 
        
                if($report!=NULL)
                {
                    $data = 
                    [
                        'parent_id'     =>$parent_id,
                        'business_id'   =>$job->business_id,
                        'candidate_id'  =>$candidate_id,
                        'sla_id'        =>$job->sla_id,       
                        'created_at'    =>date('Y-m-d H:i:s')
                    ];
                    
                    $report_id = DB::table('reports')->insertGetId($data);
                }
                else
                {
                    $report_id = $report->id;
                }
        
                // add service items
                $jaf_items_datas = DB::table('jaf_form_data')->where('candidate_id',$candidate_id)->get(); 
                //dd($jaf_items_datas);
                if(count($jaf_items_datas)>0)
                {
                    foreach($jaf_items_datas as $item){
                        $reference_type = NULL;
        
                        $r_item = DB::table('report_items')->where('jaf_id',$item->id)->first();
        
                        if($r_item!=NULL)
                        {
                            if ($item->verification_status == 'success') {
                                $data = 
                                [
                                    'jaf_data'      =>$item->form_data,
                                    'updated_at'    =>date('Y-m-d H:i:s')
                                ];
                            } 
                            else {
                                $data = 
                                [
                                    'jaf_data'      =>$item->form_data,
                                    'updated_at'    =>date('Y-m-d H:i:s')
                                ]; 
                            }
        
                            DB::table('report_items')->where('jaf_id',$item->id)->update($data);
        
                            $report_item_id = $r_item->id;
                        }
                        else{
                            if ($item->verification_status == 'success') {
                                $data = 
                                [
                                    'report_id'     =>$report_id,
                                    'service_id'    =>$item->service_id,
                                    'service_item_number'=>$item->check_item_number,
                                    'candidate_id'  =>$candidate_id,      
                                    'jaf_data'      =>$item->form_data,
                                    'jaf_id'        =>$item->id,
                                    // 'reference_type' =>  $reference_type,
                                    'created_at'    =>date('Y-m-d H:i:s')
                                ];
                            } 
                            else {
                                $data = 
                                [
                                    'report_id'     =>$report_id,
                                    'service_id'    =>$item->service_id,
                                    'service_item_number'=>$item->check_item_number,
                                    'candidate_id'  =>$candidate_id,      
                                    'jaf_data'      =>$item->form_data,
                                    'jaf_id'        =>$item->id,
                                    'is_report_output' => '0',
                                    // 'reference_type' =>  $reference_type,
                                    'created_at'    =>date('Y-m-d H:i:s')
                                ]; 
                            }
                            $report_item_id = DB::table('report_items')->insertGetId($data);
                        }
        
                        $report_item = DB::table('report_items')->where(['id'=>$report_item_id])->first();
        
                        if($report_item->service_id==17)
                        {
                            $input_data = $item->form_data;
        
                            $input_data_array = json_decode($input_data,true);
        
                            if($input_data_array!=NULL)
                            {
                                foreach($input_data_array as $key => $input)
                                {
                                    $key_val = array_keys($input); $input_val = array_values($input);
                                    if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                    {
                                        $reference_type = $input_val[0];
                                    }
                                }
                            }
                        }
        
                        DB::table('report_items')->where(['id'=>$report_item_id])->update([
                            'reference_type' => $reference_type
                        ]);
                    }
                }
            }

            echo 'done';
        }
        
    }

    public function sendLinkImport(Request $request)
    {
        $business_id = Auth::user()->id;

        if($request->isMethod('get'))
        {
            return view('admin.send-link-import');
        }

        $file = $request->excel_file;
        
        $parsed_array = Excel::toArray([], $file);
        $imported_data = array_splice($parsed_array[0], 1);
        //dd($imported_data);
        DB::beginTransaction();
        try{
            $i=0;
            if(count($imported_data)>0)
            {
                foreach ($imported_data as $value)
                {
                    $display_id = $value[1];

                    $candidateData = CandidateReinitiate::where('display_id',$display_id)->first();

                    if($candidateData!=null && $candidateData->email!=null)
                    {
                        DB::table('candidate_documents')->where('candidate_id',$candidateData->id)->insert([
                            'candidate_id' => $candidateData->id, 
                            'status' => '0',
                            'created_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);

                        $name = $candidateData->name;
                        
                        $frnid = $candidateData->frnid ? $candidateData->frnid.'-' : '';
                        $email = $candidateData->email;
                        $date=$candidateData->created_at;
                    
                        $url = url('/candidateDocumentForm',['id'=>base64_encode($candidateData->id)]);

                        $msg = "Address Proof Required";
                        $sender = User::from('users')->where(['id'=>$business_id])->first();
                        $data  = array('name'=>$name,'email'=>$email,'link'=>$url,'date'=>$date,'msg'=>$msg,'sender'=>$sender);
                        Mail::send(['html'=>'mails.send-link-form'], $data, function($message) use($email,$name,$display_id,$frnid) {
                            $message->to($email, $name)->subject
                            ($frnid.''.$name. '-' .$display_id.'-Addres Proof Required');
                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        });

                        $i++;
                    }
                }
            }

            DB::commit();
            echo 'done '.$i;
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    public function slaExportData($id)
    {
        $slaData = DB::table('customer_sla_items as csi')
                    ->select('csi.*')
                    ->join('customer_sla as c','c.id','=','csi.sla_id')
                    ->where(['c.id'=>$id])
                    ->get();
       
        $path=public_path().'/uploads/sla-export/';

        $file_name = 'sla-export-data-'.date('YmdHis').'.xlsx';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }
          
        Excel::store(new SlaExport($slaData,$id),'/uploads/sla-export/'.$file_name,'real_public'); 

        return response()->json([
            'success' => true,
            'url' => url('/').'/uploads/sla-export/'.$file_name
          ]);
    }

    public function csvExportData($id)
    {
        $slaData = DB::table('customer_sla_items as csi')
                    ->select('csi.*')
                    ->join('customer_sla as c','c.id','=','csi.sla_id')
                    ->where(['c.id'=>$id])
                    ->get();
       
        $path=public_path().'/uploads/csv-export/';

        $file_name = 'csv-export-data-'.date('YmdHis').'.csv';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }
        
        ob_end_clean(); 
        ob_start(); 
        Excel::store(new SlaExport($slaData,$id),'/uploads/csv-export/'.$file_name,'real_public'); 

        return response()->json([
            'success' => true,
            'url' => url('/').'/uploads/csv-export/'.$file_name
          ]);
    }


    public function bulkReassignUser(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $user_id = Auth::user()->id;

        DB::beginTransaction();
        try{
            
            $assign_id = $request->get('assign_id');

            $reassign_id = $request->get('reassign_id');

            $task_assgns = DB::table('task_assignments')->select('*')->where(['status'=>"1",'user_id'=>$assign_id])->get();
            //dd($task_assgns);
                $i=0;

            if(count($task_assgns)>0)
            {
                foreach($task_assgns as $task)
                {
                    DB::table('task_assignments')->where('id',$task->id)->update([
                        'status' => "0"
                    ]);

                    //dd($task);

                    $task_ass_data = DB::table('task_assignments')->where('task_id',$task->task_id)->where('status','1')->get();
                    //dd($task_ass_data);
                    if(count($task_ass_data)==0)
                    {
                            $i++;
                            $taskdata = [

                                    'business_id'=> $task->business_id,
                                    'candidate_id' =>$task->candidate_id,   
                                    'job_sla_item_id'  => $task->job_sla_item_id,
                                    'task_id'=> $task->task_id,
                                    'user_id' => $task->user_id,
                                    'service_id'  =>$task->service_id,
                                    'number_of_verifications'=>$task->number_of_verifications,
                                    'reassign_to' =>$reassign_id,
                                    'reassign_by' => $user_id,
                                    'status' =>'1',
                                    // 'tat' =>$request->input('tat'),
                                    'created_at'  => date('Y-m-d H:i:s'),
                                    'updated_at'  => date('Y-m-d H:i:s')
                            ];
                            DB::table('task_assignments')->insert($taskdata);
                    }

                }
            }

            DB::commit();

            echo 'done '.count($i);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    public function fakeGenEduList(Request $request)
    {
        $data_result = [];

        $data = DB::table('fake_educational_lists')->where('status',1)->get();

        foreach($data as $d)
        {
            $data_result[] = ['id'=>$d->id,'name' => $d->board_or_university_name];
        }

        return response()->json($data_result);
    }

    public function fakeGenEmpList(Request $request)
    {
        $data_result = [];

        $data = DB::table('fake_employment_lists')->where('status',1)->get();

        foreach($data as $d)
        {
            $data_result[] = ['id'=>$d->id,'name' => $d->company_name];
        }

        return response()->json($data_result);
    }


}