<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class SlaController extends Controller 
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    } 
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $sla = DB::table('customer_sla as sla')
                ->select('sla.*','u.first_name','u.last_name','ub.company_name')
                ->join('users as u','u.id','=','sla.business_id')
                ->join('user_businesses as ub','ub.business_id','=','sla.business_id')
                ->where(['u.business_id'=>Auth::user()->business_id])
                ->orderBy('sla.id','desc')
                ->paginate(10);

                // dd($sla);
        if($request->ajax())
            return view('clients.sla.ajax', compact('sla'));
        else
            return view('clients.sla.index', compact('sla'));
    }

     /**
     * Show the general. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla_create()
    {
        //    $business_id = Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;
        // $customers = DB::table('users as u')
        //         ->select('u.id','u.first_name','u.last_name','b.company_name')
        //         ->join('user_businesses as b','b.business_id','=','u.id')
        //         ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'client'])
        //         ->get();

        $services = DB::table('services as s')
                    ->select('s.*')
                    ->join('service_form_inputs as si','s.id','=','si.service_id')
                    ->where('s.business_id',NULL)
                    ->whereNotIn('s.type_name',['gstin'])
                    ->orwhere('s.business_id',$parent_id)
                    ->groupBy('si.service_id')
                    ->get();

        return view('clients.sla.create', compact('services'));
    }

    
      /**
     * store the data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla_save(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $max_service_tat=0;
        $rules= 
        [
            
            'name'       => 'required', 
            // 'tat'        => 'required|numeric', 
            'client_tat' => 'required|integer|min:1', 
            'services'   => 'required|array|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try
        {

                $check_name = DB::table('customer_sla')->where(['business_id'=>$business_id, 'title'=>$request->input('name')])->count();

                if($check_name > 0 ){

                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['name'=>'SLA name is already exist!']
                    ]);

                }

                //validation for no_of_verification and check_tat
                if( count($request->input('services') ) > 0 ){
                    foreach($request->input('services') as $service){

                        $rules=[
                            'service_unit-'.$service    => 'required|integer|min:1',
                            'tat-'.$service => 'required|integer|min:1',
                            'incentive-'.$service => 'required|integer|lte:tat-'.$service
                        ];
                        $customMessages=[
                            'service_unit-'.$service.'.required' => 'No of Verification is required',
                            'service_unit-'.$service.'.integer' => 'No of Verification should be numeric',
                            'service_unit-'.$service.'.min' => 'No of Verification should be atleast 1',
                            // 'service_unit-'.$service.'.max' => 'No of Verification should be Maximum 3',
                            'tat-'.$service.'.required' => 'No of TAT is required',
                            'tat-'.$service.'.integer' => 'No of TAT should be numeric',
                            'tat-'.$service.'.min' => 'No of TAT should be atleast 1',
                            'incentive-'.$service.'.required' => 'No of incentive TAT is required',
                            'incentive-'.$service.'.integer' => 'No of incentive TAT should be numeric',
                            'incentive-'.$service.'.lte' => 'No of Incentive TAT should be less than or equal to Service TAT',
                            'penalty-'.$service.'.required' => 'No of penalty TAT is required',
                            'penalty-'.$service.'.integer' => 'No of penalty TAT should be numeric',
                            'penalty-'.$service.'.gte' => 'No of penalty TAT should be greater than or equal to Service TAT',
                        ];
                            $validator = Validator::make($request->all(), $rules,$customMessages);
                            
                            if ($validator->fails()){
                                return response()->json([
                                    'success' => false,
                                    'errors' => $validator->errors()
                                ]);
                            }
                        
                        $max_service_tat =  $max_service_tat + $request->input('tat-'.$service);
                    }
                }

                //check if TAT is less than Overall Service TATs
                if($max_service_tat > $request->client_tat)
                {
                  
                    return response()->json([
                        'success' => false,
                        'custom'=>'yes',
                        'errors' => ['client_tat'=>'TAT should be greater than Overall Service TATs']
                    ]);
                    
                }

                    $data = [
                                'business_id'=> $business_id,
                                'parent_id'  => Auth::user()->parent_id,
                                'title'      => $request->input('name'),
                                'tat'        => $request->input('client_tat'),
                                'client_tat' => $request->input('client_tat'),
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s')
                            ];

                    $sla_id = DB::table('customer_sla')->insertGetId($data);

                $i = 0;
                $number_of_verifications =1;
                $no_of_tat=1;
                $incentive_tat=1;
                $penalty_tat=1;
                if( count($request->input('services') ) > 0 ){
                    foreach($request->input('services') as $service){

                    $number_of_verifications = $request->input('service_unit-'.$service);
                    $notes = $request->input('notes-'.$service);

                    $no_of_tat = $request->input('tat-'.$service);

                    $incentive_tat = $request->input('incentive-'.$service);

                    $penalty_tat = $request->input('penalty-'.$service);
                        
                        $data = [
                            'business_id'   =>$business_id,
                            'sla_id'        =>$sla_id,
                            'number_of_verifications'=>$number_of_verifications,
                            'service_id'    =>$service,
                            'notes'         =>$notes,
                            'tat'           => $no_of_tat,
                            'incentive_tat' => $incentive_tat,
                            'penalty_tat' => $penalty_tat,
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

    public function sla_view($id)
    {
        $sla_id = base64_decode($id);
        $business_id = Auth::user()->business_id;

        $parent_id=Auth::user()->parent_id;

        $sla = DB::table('customer_sla as sla')
                ->select('sla.*','u.company_name')
                ->join('user_businesses as u','u.business_id','=','sla.business_id')
                ->where(['sla.id'=>$sla_id])
                ->first();
        
        $sla_items = DB::table('customer_sla_items as sla')
        ->select('s.id','s.name','sla.number_of_verifications','sla.notes','sla.id as sla_item_id','sla.tat as check_tat','sla.incentive_tat','sla.penalty_tat','sla.price')
        ->join('services as s','s.id','=','sla.service_id')
        ->where(['sla.sla_id'=>$sla_id])
        ->get();

        $selected_services_id = [];
        foreach($sla_items as $item){
            $selected_services_id[] = $item->id;
        }

        $services= DB::table('services as s')
            ->select('s.*')
            ->join('service_form_inputs as si','s.id','=','si.service_id')
            ->where('business_id',NULL)
            ->whereNotIn('s.type_name',['gstin'])
            ->orwhere('business_id',$parent_id)
            ->orderBy('s.sort_number','asc')
            ->groupBy('si.service_id')
            ->get();

            $total_checks = DB::table('customer_sla_items')
                            ->select('number_of_verifications')
                            ->where(['sla_id'=>$sla_id])
                            ->sum('number_of_verifications');

            //  dd($total_checks);

            $total_check_price = DB::table('customer_sla_items')
                                    ->select('price')
                                    ->where(['sla_id'=>$sla_id])
                                    ->sum('price');
    
            return view('clients.sla.view',compact('services','sla','sla_items','selected_services_id','total_checks','total_check_price'));
         

    }
}
