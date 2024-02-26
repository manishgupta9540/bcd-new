<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\UserBusinessContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserBusinessContactObserver
{
    /**
     * Handle the user business contact "created" event.
     *
     * @param  \App\Models\Admin\UserBusinessContact  $userBusinessContact
     * @return void
     */
    public function created(UserBusinessContact $userBusinessContact)
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

        $user_type= DB::table('user_business_contacts')->where('id',$userBusinessContact->id)->first();
        $input_data['new'] = [
            'business_id'=>$user_type->business_id,'contact_type'=>$user_type->contact_type,'email' =>$user_type->email,'phone' => $user_type,'phone_code'=> $user_type->phone_code, 'phone_iso'=> $user_type->phone_iso,'landline_number'=> $user_type->landline_number,'created_by'=>Auth::user()->id,'created_at'=> date('Y-m-d H:i:s')
        ];
        $user_data = json_encode($input_data);
        $contact_type = '';
        if ($user_type->contact_type=='owner') {
            $contact_type = 'Owner Business Contact';
        }
        elseif ($user_type->contact_type=='dealing_officer') {
            $contact_type = 'Dealing Officer Business Contact';
        }
        elseif ($user_type->contact_type=='account_officer') {
            $contact_type = 'Account Officer Business Contact';
        }else {
            $contact_type =$user_type->contact_type;
        }
       
        // dd($user_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =Auth::user()->business_id;
        $new_activity->business_id =$user_type?$user_type->business_id:'';
        $new_activity->activity_id = $userBusinessContact->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title =$contact_type;
        $new_activity->data = $user_data;
        $new_activity->created_by = Auth::user()->id;
        $new_activity->save();
    }

    /**
     * Handle the user business contact "updated" event.
     *
     * @param  \App\Models\Admin\UserBusinessContact  $userBusinessContact
     * @return void
     */
    public function updated(UserBusinessContact $userBusinessContact)
    {
        //
    }

    /**
     * Handle the user business contact "deleted" event.
     *
     * @param  \App\Models\Admin\UserBusinessContact  $userBusinessContact
     * @return void
     */
    public function deleted(UserBusinessContact $userBusinessContact)
    {
        //
    }

    /**
     * Handle the user business contact "restored" event.
     *
     * @param  \App\Models\Admin\UserBusinessContact  $userBusinessContact
     * @return void
     */
    public function restored(UserBusinessContact $userBusinessContact)
    {
        //
    }

    /**
     * Handle the user business contact "force deleted" event.
     *
     * @param  \App\Models\Admin\UserBusinessContact  $userBusinessContact
     * @return void
     */
    public function forceDeleted(UserBusinessContact $userBusinessContact)
    {
        //
    }
}
