<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class ConfigController extends Controller
{
   public function zoneIndex()
   {
       $zones = DB::table('zone_masters')
       ->select('id','business_id','name','country_id')
       ->where('business_id',Auth::user()->business_id)
       ->groupBy('name')
       ->get();
    //    dd($zones);
       return view('admin.accounts.configs.zone.index',compact('zones'));
   }

   //Create Zone page
   public function zoneCreate()
   {
    $countries      = DB::table('countries')->get();
    $state          = DB::table('states')->where('country_id','101')->get();
       return view('admin.accounts.configs.zone.create',compact('countries','state'));
   }

   //Get cities Zonewise(selected states)
   public function getCities(Request $request)
   {
      $state_id = $request->state_id; 

      $city = DB::table('cities')->whereIn('state_id',$state_id)->get();
     //   dd($city);
      return response()->json($city);
    
   }

    //    Save Zone Data

   public function zoneSave(Request $request)
   {
        //    dd($request->city_id);

        $rules= [
         
            'name'  => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
            'country_id' =>'required',
            'state_id' =>'required',
            'city_id' =>'required',
            
         ];
     
         $customMessages = [
         'country_id.required' => 'Select at least one Option of Country',
         'state_id.required' => 'Select at least one Option of State',
         'city_id.required' => 'Select at least one Option of City',
         'name.required'=> 'Zone name is required',
         'name.regex' => 'Zone name should be in letters'
         
      ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
       
      if ($validator->fails()){
          return response()->json([
              'success' => false,
              'errors' => $validator->errors()
          ]);
      }


        $all_cities=[];
        $states = $request->state_id;
        $cities =$request->city_id;
        $all_city = DB::table('cities')->whereIn('state_id',$states)->get();
        foreach ($all_city as $c) {
          $all_cities[]= $c->id;
        }
        // var_dump($all_city);die;
        foreach ($cities as $city) {
           if (in_array($city,$all_cities)) {
               
                $state = DB::table('cities')->select('state_id')->where('id',$city)->first();

                 $data = 
                [
                    'parent_id'     =>Auth::user()->parent_id,
                    'business_id'   =>Auth::user()->business_id,
                    'name'          =>$request->name,
                    'city_id'       =>$city,
                    'state_id'       =>$state->state_id,
                    'country_id'     =>$request->country_id,
                    'created_by'    =>Auth::user()->id,
                    'created_at'    =>date('Y-m-d H:i:s') 
                ];
            
                $user_id = DB::table('zone_masters')->insertGetId($data);
           }

        }
        return response()->json([
            'success' => true,
        ]);
       

   }
      //Edit Zone page
      public function zoneEdit(Request $request)
      {
         $name       = base64_decode($request->segment(4));
         $business_id       = base64_decode($request->segment(3));
         $state_id=[];
         $city_id=[];
         // dd($business_id);
         $countries      = DB::table('countries')->get();
         $state          = DB::table('states')->where('country_id','101')->get();
         $zone =       DB::table('zone_masters')->select('name','country_id')->where(['name'=>$name,'business_id'=>$business_id])->first();
         $zone_state   = DB::table('zone_masters')->select('state_id')->where(['name'=>$name,'business_id'=>$business_id])->get();
            foreach ($zone_state as $item) {
               $state_id[]= $item->state_id;
            }
         $cities = DB::table('cities')->whereIn('state_id',$state_id)->get();
         $zone_city   = DB::table('zone_masters')->select('city_id')->where(['name'=>$name,'business_id'=>$business_id])->get();
            foreach ($zone_city as $value) {
            $city_id[]= $value->city_id;
            }

            // dd($city_id);
          return view('admin.accounts.configs.zone.edit',compact('countries','state','zone','state_id','city_id','business_id','cities'));
      }


//    Save Zone Data

   public function zoneUpdate(Request $request)
   {
      $rules= [
         
         'name'  => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
         'country_id' =>'required',
         'state_id' =>'required',
         'city_id' =>'required',
         
         
      ];
  
      $customMessages = [
         'country_id.required' => 'Select at least one Option of Country',
         'state_id.required' => 'Select at least one Option of State',
         'city_id.required' => 'Select at least one Option of City',
         'name.required'=> 'Zone name is required',
         'name.regex' => 'Zone name should be in letters'
         
      ];

         $validator = Validator::make($request->all(), $rules,$customMessages);
      
      if ($validator->fails()){
         return response()->json([
            'success' => false,
            'errors' => $validator->errors()
         ]);
      }


        //    dd($request->city_id);
        $all_cities=[];
        $states = $request->state_id;
        $cities =$request->city_id;
        $all_city = DB::table('cities')->whereIn('state_id',$states)->get();
        foreach ($all_city as $c) {
          $all_cities[]= $c->id;
        }
        // var_dump($all_city);die;
        foreach ($cities as $city) {
           if (in_array($city,$all_cities)) {
               $zone = DB::table('zone_masters')->where(['name'=>$request->name,'business_id'=>$request->business_id,'city_id'=>$city])->first();
               $state = DB::table('cities')->select('state_id')->where('id',$city)->first();
               if ($zone) {
                  $data = 
                  [
                      'parent_id'     =>Auth::user()->parent_id,
                      'business_id'   =>Auth::user()->business_id,
                      'name'          =>$request->name,
                      'city_id'       =>$city,
                      'state_id'       =>$state->state_id,
                      'country_id'     =>$request->country_id,
                      'updated_by'    =>Auth::user()->id,
                      'updated_at'    =>date('Y-m-d H:i:s') 
                  ];
              
                   DB::table('zone_masters')->where(['name'=>$request->name,'business_id'=>$request->business_id,'city_id'=>$city])->update($data);
               } else {
                  $data = 
                  [
                      'parent_id'     =>Auth::user()->parent_id,
                      'business_id'   =>Auth::user()->business_id,
                      'name'          =>$request->name,
                      'city_id'       =>$city,
                      'state_id'       =>$state->state_id,
                      'country_id'     =>$request->country_id,
                      'created_by'    =>Auth::user()->id,
                      'created_at'    =>date('Y-m-d H:i:s') 
                  ];
              
                   DB::table('zone_masters')->insertGetId($data);
               }    
           }

        }
        return response()->json([
            'success' => true,
        ]);
       

   }

      
}
