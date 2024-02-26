<?php

namespace App\Http\Controllers\API\AddressVerification\V1\Candidates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    // account login with sms otp
    public function verificationAddress(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
                    'user_id'  => 'required'
                    
                ]);


        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }
        
        $address=[];
        //input data
        $user_id  = $request->get('user_id');
        $login = DB::table('candidate_reinitiates as u')
        ->select('u.id','u.user_type','u.status')
        ->where(['u.id'=>$user_id,'u.user_type'=>'candidate'])
        ->first();
        // check login email and password with company id
        if($login ===null)
        {  
        return response()->json(['status' => 'error','message'=>'The given data was invalid.','errors'=>['error'=>['It seems like Your profile is not exist!, please contact to your agency']]], 200);
        }
        $i=0;
        $jaf_items=DB::table('jaf_form_data')
        ->where(['candidate_id'=>$user_id,'service_id'=>'1'])
        ->get();
        // dd($jaf_items);
        if(count($jaf_items)>0)
        {
           
           foreach ($jaf_items as $jaf_item) {
               $i++;
               $jaf_id =$jaf_item->id;
               $candidate_address = $jaf_item->form_data;
               if($candidate_address != null){
                
                    $input_item_data_array =  json_decode($candidate_address, true); 
                    foreach ($input_item_data_array as $key => $input) {
                        $key_val = array_keys($input);
                        $input_val = array_values($input);
                        // dd($key_val);
                        if($key_val[0]=='Address' || $key_val[0]=='address'){ 
                            
                            $addr =$input_val[0];
                            // dd($addr);
                        }
                        if($key_val[0]=='Pin Code' || $key_val[0]=='Pin code' || $key_val[0]=='pin code'){ 
                            // dd($input_val);
                            $zip =$input_val[0];
                        }
                        if($key_val[0]=='State' || $key_val[0]=='state' ){ 
                            
                            $state =$input_val[0];
                        }
                        if($key_val[0]=='City' || $key_val[0]=='CIty' || $key_val[0]=='city'){ 
                            // dd($key_val);
                            $city =$input_val[0];
                            // dd($city);
                        }
                    }

                    $address[]=['jaf_id'=>$jaf_id,'address_type'=>$jaf_item->address_type=!NULL?$jaf_item->address_type:'others','full_address'=>$addr,'zipcode'=>$zip,'city'=>$city,'state'=>$state,'country'=>'India'];

               }
                //    dd($address);
           }
                    //send data
                $successResponse = array('status'=>'success',
                    'address_count'=>$i,
                    'address' =>$address
                ); 
        }
        else
        {
            // dd('abcd');
            $successResponse=[
                'status' => 'success',
                'address_count'=>$i,
                'address' =>$address
            ];
        }
        return response()->json($successResponse, 200);
    }

      // Address Verification
      public function addressSave(Request $request)
      {
  
          //print_r($request->file('profile_photo')); die;
          $custom=['address_type.in' => 'Address Type would be permanent/Permanent or current/Current'];
          $validator = Validator::make($request->all(), [
              'user_id'     =>'required',
              'candidate_id' => 'required',
              'jaf_id'    =>'required',
              'first_name'  => 'required',
              'last_name'       => 'required',
              'phone_number'  => 'required',
              'email_address'  => 'required|email',
              'street_address'=> 'required',
              'house_building'=> 'required',
              'zipcode'       => 'required|digits:6',
              'city'          => 'required',
              'state'         => 'required',
              'country'           => 'required',
              'address_type'      => 'required|in:permanent,current,Permanent,Current',
              'address'           => 'required',
              // 'nature_of_residence' => 'required',
              // 'period_stay_from'         => 'required',
              // 'period_stay_to'         => 'required',
              // 'verifier_name'         => 'required',
              // 'relation_with_verifier'         => 'required',
              // 'nearest_location'         => 'required',
              'ownership_type'    => 'required',
              // 'profile_photo' => 'required',
              // 'address_proof_photo' => 'required',
              // 'location_photo'         => 'required',
              // 'house_photo'         => 'required',
              'draw_signature'         => 'required',
              'device_type'   => 'required|in:A,I',
              'device_token'  => 'required',
          ],$custom);
  
  
          if ($validator->fails()) {            
              return response()->json(['status' => 'error',
                                      'message'=>'The given data was invalid.',
                                      'errors'=> $validator->errors()], 200);
          }
          
  
  
          //input data
          $user_id  = $request->get('user_id');
          $candidate_id  = $request->get('candidate_id');
          $jaf_id  = $request->get('jaf_id');
          $first_name  = $request->get('first_name');
          $last_name  = $request->get('last_name');
          $phone_number  = $request->get('phone_number');
          $email_address  = $request->get('email_address');
          $street_address  = $request->get('street_address');
          $house_building  = $request->get('house_building');
          $zipcode  = $request->get('zipcode');
          $city  = $request->get('city');
          $state  = $request->get('state');
          $country_id  = $request->get('country');
          $address_type  = $request->get('address_type');
          $address  = $request->get('address');
          $nature_of_residence  = $request->get('nature_of_residence');
          $period_stay_from  = $request->get('period_stay_from');
          $period_stay_to  = $request->get('period_stay_to');
          $verifier_name  = $request->get('verifier_name');
          $relation_with_verifier  = $request->get('relation_with_verifier');
          $nearest_location  = $request->get('nearest_location');
          $ownership_type  = $request->get('ownership_type');
          $geo_full_address   = "";
          $geo_city           = $request->get('geo_city');
          $geo_state          = $request->get('geo_state');
          $geo_country        = $request->get('geo_country');
          $geo_latitude       = $request->get('geo_latitude');
          $geo_longitude      = $request->get('geo_longitude');
          $device_type   = $request->get('device_type');
          $device_token  = $request->get('device_token');
  
          $address_verification = DB::table('address_verifications')->where('jaf_id',$jaf_id)->first();
          if($address_verification !=null)
          {  
             return response()->json(['status' => 'error','error_message'=>'It seems like this address has been already verified!'], 200);
          }
          // $profile_photo = $address_proof_photo = "";
          //upload profile_photo 
          // $profile_photo_on_select=[];
          // $address_photo_on_select=[];
          // $location_photo_on_select=[];
          // $house_photo_on_select=[];
          // $sign_photo_on_select=[];
          $allowedextension=['jpg','jpeg','png','gif','svg','JPG','PDF','JPEG','PNG'];
          
          $user = DB::table('candidate_reinitiates as u')
          ->select('u.id','u.business_id','u.user_type','u.status','u.name','u.first_name','u.last_name')
          ->where(['u.id'=>$candidate_id,'u.user_type'=>'candidate'])
          ->first();
  
          if($user!=NULL)
          {
              
              if($request->draw_signature!=''){
                  // $file = base64_decode($request->draw_signature);
                  // $draw_signature_1 = date('Ymdhis').'-'.$candidate_id.'.png';
                  // $success = file_put_contents(public_path('/uploads/candidate-signature/').$draw_signature_1, $file);
                  
                  if(strpos($request->draw_signature,'data:image/') !==false)
                  {
                      $folderPath = public_path('uploads/candidate-signature/');
              
                      $image_parts = explode(";base64,", $request->draw_signature);
                          
                      $image_type_aux = explode("image/", $image_parts[0]);
                          
                      $image_type = $image_type_aux[1];
                          
                      $image_base64 = base64_decode($image_parts[1]);
  
                      // dd($image_base64);
                      
                      $draw_signature_1 = date('Ymdhis').'-'.$candidate_id.'.'.'jpeg';
                      $file = $folderPath . $draw_signature_1;
                      header('Content-Type: bitmap; charset=utf-8');
                      file_put_contents($file, $image_base64);
                  }
                  else
                  {
                      // $image = 'data:image/png;base64,'.$request->draw_signature;
                      // $image = str_replace(' ', '+', $image);
                      // $draw_signature_1 = date('Ymdhis').'-'.$candidate_id.'.'.'png';
                      // File::put(public_path().'/uploads/candidate-signature/' . $draw_signature_1, base64_decode($image)); 
  
                      $image = base64_decode($request->draw_signature);
                      $image_name ="";
                      $image_string   = $request->input('draw_signature');
  
                      //$filename = $filename;
                      $new_filename = date('Ymdhis').'-'.$candidate_id.'.jpeg';
                      // Decode Image
                      $binary=base64_decode($image_string);
                      header('Content-Type: bitmap; charset=utf-8');
                      // Images will be saved under 'www/uploads/users' folder
                      $file = fopen(public_path() . "/uploads/candidate-signature/".$new_filename, 'wb');
                      // Create File
                      fwrite($file, $binary);
                      $draw_signature_1 = $new_filename;
                  }
  
  
              }
          
  
  
              //get lat and long from manual address
              $latitude   = 28.608721;
              $longitude  = 77.348900;
              //check candidate id
          
              $address_data = [   
                  'jaf_id'        =>$jaf_id,
                  'first_name'    => $first_name,
                  'last_name'    => $last_name,
                  'email'         => $email_address,
                  'phone'         => $phone_number,
                  'business_id'   =>$user->business_id,
                  'candidate_id'  =>$candidate_id,
                  // 'selfi_photo'   =>count($profile_photo_on_select)>0?json_encode($profile_photo_on_select):$selfi_photo,
                  // 'address_proof_photo_1'=>count($address_photo_on_select)>0?json_encode($address_photo_on_select):$address_proof_1,
                  // 'location_photo'    => count($location_photo_on_select)>0?json_encode($location_photo_on_select):$location_photo_1,
                  // 'house_photo'       =>count($house_photo_on_select)>0?json_encode($house_photo_on_select):$house_photo_1,
                  // 'signature'         =>count($sign_photo_on_select)>0?json_encode($sign_photo_on_select):$draw_signature_1,
                  'signature'         =>$draw_signature_1!=""?$draw_signature_1:NULL,
                  'address_line1' =>$street_address,
                  'address_line2' =>$house_building,
                  'full_address' =>$address,
                  'nature_of_residence' =>$nature_of_residence,
                  'period_stay_from'  => $period_stay_from,
                  'period_stay_to'   => $period_stay_to,
                  'verifier_name'   => $verifier_name,
                  'relation_with_verifier' => $relation_with_verifier,
                  'landmark'          => $nearest_location,
                  'zipcode'       =>$zipcode,
                  'country_id'       =>$country_id,
                  'city_name'     =>$city,
                  'state_name'    =>$state,
                  'latitude'      =>$latitude,
                  'longitude'     =>$longitude,
                  'geo_address'   =>$geo_full_address,
                  'geo_city'      =>$geo_city,
                  'geo_state'     =>$geo_state,
                  'geo_country'   =>$geo_country,
                  'geo_latitude'  =>$geo_latitude,
                  'geo_longitude' =>$geo_longitude,
                  'address_type'  =>$address_type,
                  'ownership_type'=>$ownership_type,
                  'created_at'    =>date('Y-m-d H:i:s'),
                  'created_by'    =>$user_id,
                  
              ];
      
      
      
              DB::table('address_verifications')
              ->insert($address_data);
              //send data
              $successResponse = ['status'=>'success',
              'message'=>'Your address details has been submitted ']; 
          }
          else
          {
              $successResponse = ['status'=>'failed',
              'message'=>'Candidate ID Not Found']; 
          }
          return response()->json($successResponse, 200);
  
  
      }

}
