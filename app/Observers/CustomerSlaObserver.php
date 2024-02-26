<?php

namespace App\Observers;

use App\Models\Admin\CustomerSla;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CustomerSlaObserver
{
    /**
     * Handle the customer sla "created" event.
     *
     * @param  \App\Models\Admin\CustomerSla  $customerSla
     * @return void
     */
    public function created(CustomerSla $customerSla)
    {
        // dd($customerSla);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI']; 
        $user_type= DB::table('customer_sla')->where('id',$customerSla->id)->first();

        $input_data['new'] = [
            'business_id'=> $user_type->business_id,'parent_id' => $user_type->parent_id, 'title'=> $user_type->title,'tat'=>$user_type->tat,'client_tat' => $user_type->client_tat,'days_type'  => $user_type->days_type,'tat_type'  => $user_type->tat_type,'created_by' => $user_type->created_by?$user_type->created_by:'','created_at' => date('Y-m-d H:i:s')
          ];

        $user_data = json_encode($input_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =  $user_type->parent_id??null;
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $customerSla->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Customer Sla';
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the customer sla "updated" event.
     *
     * @param  \App\Models\Admin\CustomerSla  $customerSla
     * @return void
     */
    public function updated(CustomerSla $customerSla)
    {
        //  dd($customerSla);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI']; 
        $user_type= DB::table('customer_sla')->where('id',$customerSla->id)->first();
        if ($user_type->tat_type=='case') {
            $input_data['new'] = [
                'business_id'=> $user_type->business_id,'parent_id' => $user_type->parent_id, 'title'=> $user_type->title,'tat'=>$user_type->tat,'client_tat' => $user_type->client_tat,'days_type'  => $user_type->days_type,'tat_type' => $user_type->tat_type,  'incentive' => $customerSla->incentive,
                'penalty' => $customerSla->penalty,'updated_by' => $user_type->updated_by?$user_type->updated_by:'','updated_at' => date('Y-m-d H:i:s')
              ];
            
        }else{
            $input_data['new'] = [
                'business_id'=> $user_type->business_id,'parent_id' => $user_type->parent_id, 'title'=> $user_type->title,'tat'=>$user_type->tat,'client_tat' => $user_type->client_tat,'days_type'  => $user_type->days_type,'tat_type'  => $user_type->tat_type,'updated_by' => $user_type->updated_by?$user_type->updated_by:'','updated_at' => date('Y-m-d H:i:s')
              ];
        }
        

          $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->latest()->first();
          // dd($activity);
          $data=[];
          $data1=[];
          if ($activity) {
              $data= json_decode($activity->data,true);
            if(array_key_exists('new',$data))
            {
                $data1= $data['new'];
            
                $input_data['old'] = $data1;
            }
          }
        $user_data = json_encode($input_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =  $user_type?$user_type->parent_id:'';
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $customerSla->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='updated';
        $new_activity->activity_title ='Customer Sla';
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the customer sla "deleted" event.
     *
     * @param  \App\Models\Admin\CustomerSla  $customerSla
     * @return void
     */
    public function deleted(CustomerSla $customerSla)
    {
        //
    }

    /**
     * Handle the customer sla "restored" event.
     *
     * @param  \App\Models\Admin\CustomerSla  $customerSla
     * @return void
     */
    public function restored(CustomerSla $customerSla)
    {
        //
    }

    /**
     * Handle the customer sla "force deleted" event.
     *
     * @param  \App\Models\Admin\CustomerSla  $customerSla
     * @return void
     */
    public function forceDeleted(CustomerSla $customerSla)
    {
        //
    }
}
