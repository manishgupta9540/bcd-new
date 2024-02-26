<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\RcCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RcCheckObserver
{
    /**
     * Handle the rc check "created" event.
     *
     * @param  \App\Models\Admin\RcCheck  $rcCheck
     * @return void
     */
    public function created(RcCheck $rcCheck)
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];    
    
        // echo $url;  

        $user_type= DB::table('rc_checks')->where('id',$rcCheck->id)->first();
        $input_data['new'] = [
            'parent_id' => $rcCheck->parent_id,'business_id' => $rcCheck->business,'candidate_id' => $rcCheck->candidate_id,'service_id'=> $rcCheck->service_id, 'source_type'=>$rcCheck->source_reference, 'api_client_id' =>$rcCheck->api_client_id,'rc_number'=>$rcCheck->rc_number,'registration_date' =>$rcCheck->registration_date,'owner_name'=>$rcCheck->owner_name,'present_address'   =>$rcCheck->present_address,'permanent_address'=>$rcCheck->permanent_address,'mobile_number'  =>$rcCheck->mobile_number,'vehicle_category' =>$rcCheck->vehicle_category,'vehicle_chasis_number' =>$rcCheck->vehicle_chasis_number,'vehicle_engine_number' =>$rcCheck->vehicle_engine_number,'maker_description'     =>$rcCheck->maker_description,'maker_model'=>$rcCheck->maker_model,'body_type'=>$rcCheck->body_type,'fuel_type'=>$rcCheck->fuel_type,'color' =>$rcCheck->color,'norms_type' =>$rcCheck->norms_type,'fit_up_to'=>$rcCheck->fit_up_to,'financer' =>$rcCheck->financer,'insurance_company'=>$rcCheck->insurance_company,'insurance_policy_number'=>$rcCheck->insurance_policy_number,'insurance_upto'=>$rcCheck->insurance_upto,'manufacturing_date'=>$rcCheck->manufacturing_date,'registered_at'=>$rcCheck->registered_at,'latest_by' =>$rcCheck->latest_by,'less_info'=>$rcCheck->less_info,'tax_upto' =>$rcCheck->tax_upto,'cubic_capacity'=>$rcCheck->cubic_capacity,'vehicle_gross_weight'=>$rcCheck->vehicle_gross_weight,'no_cylinders'=>$rcCheck->no_cylinders,'seat_capacity'=>$rcCheck->seat_capacity,'sleeper_capacity'=>$rcCheck->sleeper_capacity,'standing_capacity' =>$rcCheck->standing_capacity,'wheelbase'=>$rcCheck->wheelbase,'unladen_weight'=>$rcCheck->unladen_weight,'vehicle_category_description'=>$rcCheck->vehicle_category_description,'pucc_number'=>$rcCheck->pucc_number,'pucc_upto'=>$rcCheck->pucc_upto,'masked_name'=>$rcCheck->masked_name,'is_verified' =>$rcCheck->is_verified,'is_rc_exist' =>$rcCheck->price,'price'=>$rcCheck->price,'used_by' =>$rcCheck->used_by,'user_id' => $rcCheck->user_id,'created_by'=> $user_type->user_id?$user_type->user_id:'','created_at'=>$user_type->created_at
        ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type?$user_type->parent_id:'';
        $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
        $new_activity->activity_id = $rcCheck->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='RC Check';
        $new_activity->data = $user_data;
        $new_activity->created_by =  $user_type->user_id?$user_type->user_id:'';
        $new_activity->save();
    }

    /**
     * Handle the rc check "updated" event.
     *
     * @param  \App\Models\Admin\RcCheck  $rcCheck
     * @return void
     */
    public function updated(RcCheck $rcCheck)
    {
        //
    }

    /**
     * Handle the rc check "deleting" event.
     *
     * @param  \App\Models\Admin\RcCheck  $rcCheck
     * @return void
     */
    public function deleting(RcCheck $rcCheck)
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];    
    
        // echo $url;  

        if($rcCheck)
        {
            $user_type= DB::table('rc_checks')->where('id',$rcCheck->id)->first();
            $input_data['new'] = [
                'parent_id' => $rcCheck->parent_id,'business_id' => $rcCheck->business,'candidate_id' => $rcCheck->candidate_id,'service_id'=> $rcCheck->service_id, 'source_type'=>$rcCheck->source_reference, 'api_client_id' =>$rcCheck->api_client_id,'rc_number'=>$rcCheck->rc_number,'registration_date' =>$rcCheck->registration_date,'owner_name'=>$rcCheck->owner_name,'present_address'   =>$rcCheck->present_address,'permanent_address'=>$rcCheck->permanent_address,'mobile_number'  =>$rcCheck->mobile_number,'vehicle_category' =>$rcCheck->vehicle_category,'vehicle_chasis_number' =>$rcCheck->vehicle_chasis_number,'vehicle_engine_number' =>$rcCheck->vehicle_engine_number,'maker_description'     =>$rcCheck->maker_description,'maker_model'=>$rcCheck->maker_model,'body_type'=>$rcCheck->body_type,'fuel_type'=>$rcCheck->fuel_type,'color' =>$rcCheck->color,'norms_type' =>$rcCheck->norms_type,'fit_up_to'=>$rcCheck->fit_up_to,'financer' =>$rcCheck->financer,'insurance_company'=>$rcCheck->insurance_company,'insurance_policy_number'=>$rcCheck->insurance_policy_number,'insurance_upto'=>$rcCheck->insurance_upto,'manufacturing_date'=>$rcCheck->manufacturing_date,'registered_at'=>$rcCheck->registered_at,'latest_by' =>$rcCheck->latest_by,'less_info'=>$rcCheck->less_info,'tax_upto' =>$rcCheck->tax_upto,'cubic_capacity'=>$rcCheck->cubic_capacity,'vehicle_gross_weight'=>$rcCheck->vehicle_gross_weight,'no_cylinders'=>$rcCheck->no_cylinders,'seat_capacity'=>$rcCheck->seat_capacity,'sleeper_capacity'=>$rcCheck->sleeper_capacity,'standing_capacity' =>$rcCheck->standing_capacity,'wheelbase'=>$rcCheck->wheelbase,'unladen_weight'=>$rcCheck->unladen_weight,'vehicle_category_description'=>$rcCheck->vehicle_category_description,'pucc_number'=>$rcCheck->pucc_number,'pucc_upto'=>$rcCheck->pucc_upto,'masked_name'=>$rcCheck->masked_name,'is_verified' =>$rcCheck->is_verified,'is_rc_exist' =>$rcCheck->price,'price'=>$rcCheck->price,'used_by' =>$rcCheck->used_by,'user_id' => $rcCheck->user_id,'created_by'=> $user_type->created_by?$user_type->created_by:'','created_at'=>$user_type->created_at
            ];

            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','RC Check')->latest()->first();
            // dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                if(array_key_exists('new',$data))
                {
                    $data1= $data['new'];
                    //dd($data1);
                    $input_data['old'] = $data1;
                }
            }

            $user_data = json_encode($input_data);

            $new_activity = new ActivityLog();
            $new_activity->parent_id =$user_type?$user_type->parent_id:'';
            $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
            $new_activity->activity_id = $rcCheck->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='RC Check';
            $new_activity->data = $user_data;
            $new_activity->created_by =  Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the rc check "deleted" event.
     *
     * @param  \App\Models\Admin\RcCheck  $rcCheck
     * @return void
     */
    public function deleted(RcCheck $rcCheck)
    {
        //
    }

    /**
     * Handle the rc check "restored" event.
     *
     * @param  \App\Models\Admin\RcCheck  $rcCheck
     * @return void
     */
    public function restored(RcCheck $rcCheck)
    {
        //
    }

    /**
     * Handle the rc check "force deleted" event.
     *
     * @param  \App\Models\Admin\RcCheck  $rcCheck
     * @return void
     */
    public function forceDeleted(RcCheck $rcCheck)
    {
        //
    }
}
