<?php

namespace App\Observers;

use App\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // dd($user);
        //    $input_data=[];
   
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
    
        $user_type= DB::table('users')->where('id',$user->id)->first();
        $input_data['new'] = [
            'user_type'=>$user_type->user_type,'client_emp_code'=>$user_type->client_emp_code,'entity_code'=>$user_type->entity_code,'name'=>$user_type->name,'first_name'=>$user_type->first_name,'middle_name'=>$user_type->middle_name,'last_name'=>$user_type->last_name,'father_name'=>$user_type->father_name,'aadhar_number'=>$user_type->aadhar_number,'dob'=>$user_type->dob,'gender'=>$user_type->gender,'email'=>$user_type->email,'phone'=>$user_type->phone,'phone_code'=>$user_type->phone_code,'phone_iso'=>$user_type->phone_iso,'created_by'=>$user_type->created_by,'created_at'=>$user_type->created_at
          ];

        $user_data = json_encode($input_data);
        // dd($user_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type?$user_type->parent_id:'';
        $new_activity->business_id =$user_type->user_type=='customer'?$user->id:$user_type->business_id;
        $new_activity->activity_id = $user->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title =$user_type->user_type;
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by;
        $new_activity->save();
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // dd('msg');
        // exit();
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];    
        if($user)
        {

            $user_type= DB::table('users')->where('id',$user->id)->first();
            $input_data['new'] = [
                'user_type'=>$user_type->user_type,'client_emp_code'=>$user_type->client_emp_code,'entity_code'=>$user_type->entity_code,'name'=>$user_type->name,'first_name'=>$user_type->first_name,'middle_name'=>$user_type->middle_name,'last_name'=>$user_type->last_name,'father_name'=>$user_type->father_name,'aadhar_number'=>$user_type->aadhar_number,'dob'=>$user_type->dob,'gender'=>$user_type->gender,'email'=>$user_type->email,'phone'=>$user_type->phone,'phone_code'=>$user_type->phone_code,'phone_iso'=>$user_type->phone_iso,'updated_by'=>$user_type->updated_by,'updated_at'=>$user_type->updated_at
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->latest()->first();
            // dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                if(array_key_exists('new',$data))
                {
                    $data1= $data['new'];
                    //    dd($data1);
                    $input_data['old'] = $data1;
                }
            }
        
            $user_data = json_encode($input_data);
            // $user_type= DB::table('users')->select('parent_id','business_id')->where('id',$user->id)->first();
            $new_activity = new ActivityLog();
            $new_activity->parent_id =  $user_type?$user_type->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $user->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title =$user_type->user_type;
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the user "deleting" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(User $user)
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

        if($user)
        {
            $user_type= DB::table('users')->where('id',$user->id)->first();
            $input_data['new'] = [
                'user_type'=>$user_type->user_type,'client_emp_code'=>$user_type->client_emp_code,'entity_code'=>$user_type->entity_code,'name'=>$user_type->name,'first_name'=>$user_type->first_name,'middle_name'=>$user_type->middle_name,'last_name'=>$user_type->last_name,'father_name'=>$user_type->father_name,'aadhar_number'=>$user_type->aadhar_number,'dob'=>$user_type->dob,'gender'=>$user_type->gender,'email'=>$user_type->email,'phone'=>$user_type->phone,'phone_code'=>$user_type->phone_code,'phone_iso'=>$user_type->phone_iso,'updated_by'=>$user_type->updated_by,'updated_at'=>$user_type->updated_at
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title',$user_type->user_type)->latest()->first();
            // dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                if(array_key_exists('new',$data))
                {
                    $data1= $data['new'];
                    //    dd($data1);
                    $input_data['old'] = $data1;
                }
            }

            $user_data = json_encode($input_data);
            // $user_type= DB::table('users')->select('parent_id','business_id')->where('id',$user->id)->first();
            $new_activity = new ActivityLog();
            $new_activity->parent_id =  $user_type?$user_type->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $user->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title =$user_type->user_type;
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
