<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\UserBusiness;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserBusinessObserver
{
    /**
     * Handle the user business "created" event.
     *
     * @param  \App\Models\Admin\UserBusiness  $userBusiness
     * @return void
     */
    public function created(UserBusiness $userBusiness)
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

        $user_type= DB::table('user_businesses')->where('id',$userBusiness->id)->first();
        $input_data['new'] = [
            'business_id'=>$user_type->business_id,'company_name' =>$user_type->company_name,'address_line1' =>$user_type->address_line1,'zipcode'=>$user_type->zipcode, 'city_id'=>$user_type->city_id,'state_id'=>$user_type->state_id,'country_id' =>$user_type->country_id,'email' =>$user_type->email,'phone' => $user_type,'phone_code'=> $user_type->phone_code, 'phone_iso'=> $user_type->phone_iso,'gst_number'=> $user_type->gst_number,'gst_attachment'=> $user_type->gst_attachment,'gst_exempt' => $user_type->gst_exempt,'is_gst_verified'=> $user_type->is_gst_verified,'tin_number'=> $user_type->tin_number,'hr_name'=> $user_type->hr_name,'work_order_date'=>$user_type->work_order_date,'work_operating_date'=> $user_type->work_operating_date,'billing_detail'=> $user_type->billing_detail, 'pan_number' => $user_type->pan_number,'is_pan_verified'> $user_type->is_pan_verified,'contract_signed_by'=> $user_type->contract_signed_by,'website' => $user_type->website,'created_by'=>Auth::user()->id,'created_at'=> date('Y-m-d H:i:s')
        ];

        $user_data = json_encode($input_data);
        // dd($user_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =Auth::user()->business_id;
        $new_activity->business_id =$user_type?$user_type->business_id:'';
        $new_activity->activity_id = $userBusiness->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Customer Business';
        $new_activity->data = $user_data;
        $new_activity->created_by = Auth::user()->id;
        $new_activity->save();
    }

    /**
     * Handle the user business "updated" event.
     *
     * @param  \App\Models\Admin\UserBusiness  $userBusiness
     * @return void
     */
    public function updated(UserBusiness $userBusiness)
    {
        //
    }

    /**
     * Handle the user business "deleted" event.
     *
     * @param  \App\Models\Admin\UserBusiness  $userBusiness
     * @return void
     */
    public function deleted(UserBusiness $userBusiness)
    {
        //
    }

    /**
     * Handle the user business "restored" event.
     *
     * @param  \App\Models\Admin\UserBusiness  $userBusiness
     * @return void
     */
    public function restored(UserBusiness $userBusiness)
    {
        //
    }

    /**
     * Handle the user business "force deleted" event.
     *
     * @param  \App\Models\Admin\UserBusiness  $userBusiness
     * @return void
     */
    public function forceDeleted(UserBusiness $userBusiness)
    {
        //
    }
}
