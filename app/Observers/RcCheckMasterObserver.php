<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\RcCheckMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RcCheckMasterObserver
{
    /**
     * Handle the rc check master "created" event.
     *
     * @param  \App\Models\Admin\RcCheckMaster  $rcCheckMaster
     * @return void
     */
    public function created(RcCheckMaster $rcCheckMaster)
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

        $user_type= DB::table('rc_check_masters')->where('id',$rcCheckMaster->id)->first();
        $input_data['new'] = [
            'parent_id' => $rcCheckMaster->parent_id,'business_id' => $rcCheckMaster->business,'candidate_id' => $rcCheckMaster->candidate_id,'service_id'=> $rcCheckMaster->service_id, 'source_type'=>$rcCheckMaster->source_reference, 'api_client_id' =>$rcCheckMaster->api_client_id,'rc_number'=>$rcCheckMaster->rc_number,'registration_date' =>$rcCheckMaster->registration_date,'owner_name'=>$rcCheckMaster->owner_name,'present_address'   =>$rcCheckMaster->present_address,'permanent_address'=>$rcCheckMaster->permanent_address,'mobile_number'  =>$rcCheckMaster->mobile_number,'vehicle_category' =>$rcCheckMaster->vehicle_category,'vehicle_chasis_number' =>$rcCheckMaster->vehicle_chasis_number,'vehicle_engine_number' =>$rcCheckMaster->vehicle_engine_number,'maker_description'     =>$rcCheckMaster->maker_description,'maker_model'=>$rcCheckMaster->maker_model,'body_type'=>$rcCheckMaster->body_type,'fuel_type'=>$rcCheckMaster->fuel_type,'color' =>$rcCheckMaster->color,'norms_type' =>$rcCheckMaster->norms_type,'fit_up_to'=>$rcCheckMaster->fit_up_to,'financer' =>$rcCheckMaster->financer,'insurance_company'=>$rcCheckMaster->insurance_company,'insurance_policy_number'=>$rcCheckMaster->insurance_policy_number,'insurance_upto'=>$rcCheckMaster->insurance_upto,'manufacturing_date'=>$rcCheckMaster->manufacturing_date,'registered_at'=>$rcCheckMaster->registered_at,'latest_by' =>$rcCheckMaster->latest_by,'less_info'=>$rcCheckMaster->less_info,'tax_upto' =>$rcCheckMaster->tax_upto,'cubic_capacity'=>$rcCheckMaster->cubic_capacity,'vehicle_gross_weight'=>$rcCheckMaster->vehicle_gross_weight,'no_cylinders'=>$rcCheckMaster->no_cylinders,'seat_capacity'=>$rcCheckMaster->seat_capacity,'sleeper_capacity'=>$rcCheckMaster->sleeper_capacity,'standing_capacity' =>$rcCheckMaster->standing_capacity,'wheelbase'=>$rcCheckMaster->wheelbase,'unladen_weight'=>$rcCheckMaster->unladen_weight,'vehicle_category_description'=>$rcCheckMaster->vehicle_category_description,'pucc_number'=>$rcCheckMaster->pucc_number,'pucc_upto'=>$rcCheckMaster->pucc_upto,'masked_name'=>$rcCheckMaster->masked_name,'is_verified' =>$rcCheckMaster->is_verified,'is_rc_exist' =>$rcCheckMaster->price,'price'=>$rcCheckMaster->price,'used_by' =>$rcCheckMaster->used_by,'user_id' => $rcCheckMaster->user_id,'created_by'=> $user_type->created_by?$user_type->created_by:'','created_at'=>$user_type->created_at
        ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type->parent_id??null;
        $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
        $new_activity->activity_id = $rcCheckMaster->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='RC Check Master';
        $new_activity->data = $user_data;
        $new_activity->created_by =  $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the rc check master "updated" event.
     *
     * @param  \App\Models\Admin\RcCheckMaster  $rcCheckMaster
     * @return void
     */
    public function updated(RcCheckMaster $rcCheckMaster)
    {
        //
    }

    /**
     * Handle the rc check master "deleted" event.
     *
     * @param  \App\Models\Admin\RcCheckMaster  $rcCheckMaster
     * @return void
     */
    public function deleted(RcCheckMaster $rcCheckMaster)
    {
        //
    }

    /**
     * Handle the rc check master "restored" event.
     *
     * @param  \App\Models\Admin\RcCheckMaster  $rcCheckMaster
     * @return void
     */
    public function restored(RcCheckMaster $rcCheckMaster)
    {
        //
    }

    /**
     * Handle the rc check master "force deleted" event.
     *
     * @param  \App\Models\Admin\RcCheckMaster  $rcCheckMaster
     * @return void
     */
    public function forceDeleted(RcCheckMaster $rcCheckMaster)
    {
        //
    }
}
