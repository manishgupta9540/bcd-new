<?php

namespace App\Observers;

use App\Models\Admin\PanCheck;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanCheckObserver
{
    /**
     * Handle the pan check "created" event.
     *
     * @param  \App\Models\Admin\PanCheck  $panCheck
     * @return void
     */
    public function created(PanCheck $panCheck)
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

        $user_type= DB::table('pan_checks')->where('id',$panCheck->id)->first();
        $input_data['new'] = [
            'parent_id' => $panCheck->parent_id,'business_id' => $panCheck->business,'candidate_id' => $panCheck->candidate_id,'service_id'=> $panCheck->service_id, 'source_type'=>$panCheck->source_reference,'pan_number' =>$panCheck->aadhar_number,'full_name'=>$panCheck->full_name, 'category' =>$panCheck->category,'is_verified' =>$panCheck->is_verified,'is_pan_exist' =>$panCheck->price,'price'=>$panCheck->price,'used_by' =>$panCheck->used_by,'user_id' => $panCheck->user_id,'created_by'=>Auth::user()->id,'created_at'=>$user_type->created_at
        ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type?$user_type->parent_id:'';
        $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
        $new_activity->activity_id = $panCheck->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='PAN Check';
        $new_activity->data = $user_data;
        $new_activity->created_by = Auth::user()->id;
        $new_activity->save();
    }

    /**
     * Handle the pan check "updated" event.
     *
     * @param  \App\Models\Admin\PanCheck  $panCheck
     * @return void
     */
    public function updated(PanCheck $panCheck)
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
        if($panCheck)
        {        
            $user_type= DB::table('pan_checks')->where('id',$panCheck->id)->first();
            $input_data['new'] = [
                'parent_id' => $panCheck->parent_id,'business_id' => $panCheck->business,'candidate_id' => $panCheck->candidate_id,'service_id'=> $panCheck->service_id, 'source_type'=>$panCheck->source_reference,'pan_number' =>$panCheck->aadhar_number,'full_name'=>$panCheck->full_name, 'category' =>$panCheck->category,'is_verified' =>$panCheck->is_verified,'is_pan_exist' =>$panCheck->price,'price'=>$panCheck->price,'used_by' =>$panCheck->used_by,'user_id' => $panCheck->user_id,'created_by'=> $user_type->created_by?$user_type->created_by:'','created_at'=>$user_type->created_at
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
                    //dd($data1);
                    $input_data['old'] = $data1;
                }
            }

            $user_data = json_encode($input_data);
            
            $new_activity = new ActivityLog();
            $new_activity->parent_id =$user_type?$user_type->parent_id:'';
            $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
            $new_activity->activity_id = $panCheck->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='PAN Check';
            $new_activity->data = $user_data;
            $new_activity->created_by =  $user_type->created_by?$user_type->created_by:'';
            $new_activity->save();
        }
    }

    /**
     * Handle the pan check "deleting" event.
     *
     * @param  \App\Models\Admin\PanCheck  $panCheck
     * @return void
     */
    public function deleting(PanCheck $panCheck)
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
        if($panCheck)
        {        
            $user_type= DB::table('pan_checks')->where('id',$panCheck->id)->first();
            $input_data['new'] = [
                'parent_id' => $panCheck->parent_id,'business_id' => $panCheck->business,'candidate_id' => $panCheck->candidate_id,'service_id'=> $panCheck->service_id, 'source_type'=>$panCheck->source_reference,'pan_number' =>$panCheck->aadhar_number,'full_name'=>$panCheck->full_name, 'category' =>$panCheck->category,'is_verified' =>$panCheck->is_verified,'is_pan_exist' =>$panCheck->price,'price'=>$panCheck->price,'used_by' =>$panCheck->used_by,'user_id' => $panCheck->user_id,'created_by'=> $user_type->created_by?$user_type->created_by:'','created_at'=>$user_type->created_at
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','PAN Check')->latest()->first();
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
            $new_activity->activity_id = $panCheck->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='PAN Check';
            $new_activity->data = $user_data;
            $new_activity->created_by =  Auth::user()->id;
            $new_activity->save();
        }

    }

    /**
     * Handle the pan check "deleted" event.
     *
     * @param  \App\Models\Admin\PanCheck  $panCheck
     * @return void
     */
    public function deleted(PanCheck $panCheck)
    {
        //
    }

    /**
     * Handle the pan check "restored" event.
     *
     * @param  \App\Models\Admin\PanCheck  $panCheck
     * @return void
     */
    public function restored(PanCheck $panCheck)
    {
        //
    }

    /**
     * Handle the pan check "force deleted" event.
     *
     * @param  \App\Models\Admin\PanCheck  $panCheck
     * @return void
     */
    public function forceDeleted(PanCheck $panCheck)
    {
        //
    }
}
