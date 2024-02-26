<?php
namespace App\Helpers;

use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use App\Models\Admin\ActionMaster;
use App\Models\Admin\RoleMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use PhpParser\Node\Stmt\Else_;
use Illuminate\Support\Facades\Crypt;
use Imagick;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Models\Admin\CandidateReinitiate;
use Illuminate\Support\Facades\File;
use App\Models\NotificationControl;
use App\Traits\CommonTrait;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helper
 *
 * @author Mithilesh Sah
 */

class Helper {


    /*
    * @return mix
    */
    
    public static function company_logo(int $business_id)
    {
        $res_data = "";
        $logo_data = DB::table('users')
                    ->select('company_logo','company_logo_file_platform')                
                    ->where(['business_id'=>$business_id])
                    ->first();

                if($logo_data !=null){

                    if(stripos($logo_data->company_logo_file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/company-logo/';

                        $file_name = $logo_data->company_logo;
    
                        $s3_config = S3ConfigTrait::s3Config();
    
                        $disk = Storage::disk('s3');
    
                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                'Key'                        => $filePath.$file_name,
                                'ResponseContentDisposition' => 'attachment;'//for download
                        ]);
    
                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');
    
                        $res_data = (string)$req->getUri();
                    }
                    else
                    {
                        $res_data = url('/').'/uploads/company-logo/'.$logo_data->company_logo;  
                    }

                    $res_data = "<img style='height:45px; object-fit:contain; width:150px;' src='".$res_data."' alt=''>";  
                }
                if( $logo_data->company_logo ==null){

                $company_data = DB::table('user_businesses')
                    ->select('company_name')                
                    ->where(['business_id'=>$business_id])
                    ->first();

                    if($company_data !=null){
                        $res_data = $company_data->company_name;
                    }
                }

        return $res_data;
        
    }

    public static function get_footer_address($businessId)
    {
        $res_data = NULL;
        $address = DB::table('customer_p_d_f_addresses')->where(['business_id'=>$businessId,'status'=>'1'])->first();
        if($address){
            $res_data = $address->address;
        }
        return $res_data;
    }
    public static function get_footer_addresses($businessId)
    {
        $res_data = NULL;
        $address = DB::table('customer_p_d_f_addresses')->where(['business_id'=>$businessId])->first();
        if($address){
            $res_data = $address->address;
        }
        return $res_data;
    }

    public static function get_default_address($parent_id)
    {
        $res_data = NULL;
        $defaultaddress = DB::table('user_businesses')->where(['business_id'=>$parent_id])->first();
        if($defaultaddress){
            $res_data = $defaultaddress;
        }
        return $res_data;
        
    }
    public static function company_logo_path(int $business_id)
    {
        $res_data = "";
        $logo_data = DB::table('users') 
                    ->select('company_logo','company_logo_file_platform')                
                    ->where(['business_id'=>$business_id])
                    ->first();

                if($logo_data !=null){

                    if(stripos($logo_data->company_logo_file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/company-logo/';

                        $file_name = $logo_data->company_logo;
    
                        $s3_config = S3ConfigTrait::s3Config();
    
                        $disk = Storage::disk('s3');
    
                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                'Key'                        => $filePath.$file_name,
                                'ResponseContentDisposition' => 'attachment;'//for download
                        ]);
    
                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');
    
                        $res_data = (string)$req->getUri();
                    }
                    else
                    {
                        $res_data = url('/').'/uploads/company-logo/'.$logo_data->company_logo;    
                    }
                }
                
                if( $logo_data->company_logo ==null){

                $company_data = DB::table('user_businesses')
                    ->select('company_name')                
                    ->where(['business_id'=>$business_id])
                    ->first();

                    if($company_data !=null){
                        $res_data = $company_data->company_name;
                    }
                }

        return $res_data;
        
    }

    /*
    * @return mix
    */
    
    public static function client_company_logo(int $business_id)
    {
        $res_data = "";
        $logo_data = DB::table('users')
                    ->select('company_logo','company_logo_file_platform')                
                    ->where(['business_id'=>$business_id])
                    ->first();

                if($logo_data !=null){

                    if(stripos($logo_data->company_logo_file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/company-logo/';

                        $file_name = $logo_data->company_logo;
    
                        $s3_config = S3ConfigTrait::s3Config();
    
                        $disk = Storage::disk('s3');
    
                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                'Key'                        => $filePath.$file_name,
                                'ResponseContentDisposition' => 'attachment;'//for download
                        ]);
    
                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');
    
                        $res_data = (string)$req->getUri();
                    }
                    else
                    {
                        $res_data = url('/').'/uploads/company-logo/'.$logo_data->company_logo;  
                    }

                    $res_data = "<img style='height:45px; object-fit:contain; width:150px;' src='".$res_data."' alt=''>";  
                }
                if( $logo_data->company_logo ==null){

                $company_data = DB::table('user_businesses')
                    ->select('company_name')                
                    ->where(['business_id'=>$business_id])
                    ->first();

                    if($company_data !=null){
                        $res_data = $company_data->company_name;
                    }
                }

        return $res_data;
        
    }

    //
    public static function company_name(int $business_id)
    {
        $res_data = "";
        $company_data = DB::table('user_businesses')
            ->select('company_name')                
            ->where(['business_id'=>$business_id])
            ->first();

            if($company_data !=null){
                $res_data = ucfirst($company_data->company_name);
            }

        return $res_data;
        
    }

    public static function company_short_name(int $business_id)
    {
        $res_data = "";
        $company_data = DB::table('user_businesses')
            ->select('company_name','company_short_name')                
            ->where(['business_id'=>$business_id])
            ->first();

            if($company_data !=null){
                $res_data =$company_data->company_short_name?$company_data->company_short_name:ucfirst($company_data->company_name);
            }

        return $res_data;
        
    }
    // Role Name from User table
    public static function role_name($role_id)
    {
        $res_data = "";
        $user_data = DB::table('role_masters')
            ->select('role')                
            ->where(['id'=>$role_id])
            ->first();

            if($user_data !=null){
                $res_data = ucfirst($user_data->role);
            }

        return $res_data;
        
    }


    // User Name from User table
    public static function user_name($user_id)
    {
        $res_data = "";
        $user_data = DB::table('users')
            ->select('name')                
            ->where(['id'=>$user_id])
            ->first();

            if($user_data !=null){
                $res_data = ucfirst($user_data->name);
            }

        return $res_data;
        
    }

    // User Name from User table
    public static function user_first_name($user_id)
    {
        $res_data = "";
        $user_data = DB::table('users')
            ->select('first_name')                
            ->where(['id'=>$user_id])
            ->first();

            if($user_data !=null){
                $res_data = ucfirst($user_data->first_name);
            }

        return $res_data;
        
    }
    
    public static function user_reference_id($user_id)
    {
        $res_data = "";
        $user_data = DB::table('users')
            ->select('display_id')                
            ->where(['id'=>$user_id])
            ->first();

            if($user_data !=null){
                $res_data = ucfirst($user_data->display_id);
            }

        return $res_data;
        
    }

    public static function parent_company_name(int $business_id)
    {
        $res_data = "";
        $company_data = DB::table('user_businesses')
            ->select('company_name')                
            ->where(['business_id'=>$business_id])
            ->first();

            if($company_data !=null){
                $res_data = ucfirst($company_data->company_name);
            }

        return $res_data;
        
    }

    public static function user_businesses(int $business_id)
    {
        $data=NULL;
        $data=DB::table('user_businesses')
                ->where(['business_id'=>$business_id])
                ->first();
        return $data;
    }

    //
    public static function report_company_name(int $business_id)
    {
        $res_data = "";
        $company_data = DB::table('user_businesses')
            ->select('company_name')                
            ->where(['business_id'=>$business_id])
            ->first();

            if($company_data !=null){
                $res_data = ucfirst($company_data->company_name);
            }

        $report_company_data = DB::table('users')
            ->select('report_company_name')                
            ->where(['id'=>$business_id])
            ->first();
        if(($report_company_data !=NULL && $report_company_data !="") && ($report_company_data->report_company_name !=NULL && $report_company_data->report_company_name !="")){
            $res_data = $report_company_data->report_company_name;
        }

        return $res_data;
        
    }

     //first word
     public static function company_first_name(int $business_id)
     {
         $res_data = "";
         $company_data = DB::table('user_businesses')
             ->select('company_name')                
             ->where(['business_id'=>$business_id])
             ->first();
  
             if($company_data !=null){
                $arr = explode(' ',trim($company_data->company_name));
                 $res_data = ucfirst($arr[0]);
             }
 
         return $res_data;
         
     }

     //first word
     public static function report_company_first_name(int $candidate_id)
     {
         $res_data = "";


         $user_business_id  = DB::table('candidate_reinitiates')
             ->select('parent_id')                
             ->where(['id'=>$candidate_id])
             ->first();

         $company_data = DB::table('user_businesses')
             ->select('company_name')                
             ->where(['business_id'=>$user_business_id->parent_id])
             ->first();
  
             if($company_data !=null){
                $arr = explode(' ',trim($company_data->company_name));
                 $res_data = ucfirst($arr[0]);
             }
 
         return $res_data;
         
     }

     public static function company_sort_name($business_id)
     {
        //  dd($business_id);
         $res_data = "";
         $company_data = DB::table('user_businesses')
             ->select('company_short_name','company_name')                
             ->where(['business_id'=>$business_id])
             ->first();
 
            if($company_data->company_short_name !=null){
                $res_data = $company_data->company_short_name;
            } else {
                $arr = explode(' ',trim($company_data->company_name));
                $res_data = ucfirst($arr[0]);
             }
 
         return $res_data;
         
     }
     
    // get superdmin id 
    //
    public static function get_superadmin_id()
    {
        $res_data = "";
        $data = DB::table('users')
            ->select('id')                
            ->where(['user_type'=>'superadmin'])
            ->first();

            if($data !=null){
                $res_data = $data->id;
            }
        return $res_data;
    }

    /*
    * @return mix
    */
    public static function get_approval_status_color_name($report_id)
    {
        $res_data = "";
        $data = DB::table('reports')
                    ->select('approval_status_id')            
                    ->where(['id'=>$report_id])
                    ->first();
            if($data !=null && $data->approval_status_id!=NULL){
                $status = DB::table('report_status_masters')
                        ->select('color_name','color_code')            
                        ->where(['id'=>$data->approval_status_id])
                        ->first();
                
                        $color = strtolower($status->color_name);
                $res_data =  $status->color_name;
            }
        return $res_data;
    }
    
    /*
    * @return mix
    */
    public static function get_approval_status_name($report_item_id)
    {
        $res_data = "";
        $data = DB::table('report_items')
                    ->select('approval_status_id')            
                    ->where(['id'=>$report_item_id])
                    ->first();
            if($data !=null){
                $status = DB::table('report_status_masters')
                        ->select('name','color_code')            
                        ->where(['id'=>$data->approval_status_id])
                        ->first();
                        if ($status) {
                            $res_data =  $status->name;
                        }
                
            }
        return $res_data;
    }

     /*
    * @return mix
    */
    public static function get_verification_status($jaf_id)
    {
        $res_data = "";
        $data = DB::table('jaf_form_data')
                    ->select('verification_status')            
                    ->where(['id'=>$jaf_id])
                    ->first();
            if($data !=null){
                // $status = DB::table('report_status_masters')
                //         ->select('name','color_code')            
                //         ->where(['id'=>$data->approval_status_id])
                //         ->first();
                $res_data =  $data->verification_status;
            }
        return $res_data;
    }

     /*
    * @return mix
    */
    public static function get_report_verification_status($jaf_id)
    {
        $res_data = "";
        $data = DB::table('report_items')
                    ->select('approval_status_id')            
                    ->where(['id'=>$jaf_id])
                    ->first();
            // if($data !=null){
                // $status = DB::table('report_status_masters')
                //         ->select('name','color_code')            
                //         ->where(['id'=>$data->approval_status_id])
                //         ->first();
                $res_data =  $data->approval_status_id;
            // }
        return $res_data;
    }
    /*
    * @return mix
    */
    public static function get_service_name($service_id)
    {
        $res_data = "";
        $data = DB::table('services')
                    ->select('name')            
                    ->where(['id'=>$service_id])
                    ->first();
            if($data !=null){
                $res_data = $data->name;
            }
        return $res_data;
    }

    public static function get_service_name_slot($service_id)
    {
        $service_id_set=explode(',',$service_id);
        $res_data = '';
        $data = DB::table('services')
                    ->select('name')            
                    ->whereIn('id',$service_id_set)
                    ->get();
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    $res_data .=' <span class="badge badge-secondary" style="padding: 5px;
                    font-size: 11px;">' .$value->name .'</span>';
                }
                
            }
        return $res_data;
    }
    /*
    * @return mix
    */
    public static function get_report_item($report_item_id)
    {
        $res_data = "";
        $data = DB::table('report_items')            
                    ->where(['id'=>$report_item_id])
                    ->first();
            if($data !=null){
                $res_data = $data;
            }
        return $res_data;
    }

    /*
    * @return mix
    */
    public static function get_check_item_count($report_item_id)
    {
        $res_data = "";
        $data = DB::table('report_items')
                    ->select('name')            
                    ->where(['id'=>$report_item_id])
                    ->first();
            if($data !=null){
                $res_data = $data;
            }
        return $res_data;
    }

    /*
    * @return mix
    */
    public static function get_check_item_number($report_id, $item_id)
    {
            $res_data = "";
            $count = DB::table('report_items')          
                    ->where(['report_id'=>$report_id])
                    ->count();

            $data = DB::table('report_items')
                    ->select('service_item_number')            
                    ->where(['id'=>$item_id])
                    ->first();

            if($count > 0){
                $res_data = $data->service_item_number;
            }
        return $res_data;
    }

    /*
    * @return mix
    */
    public static function get_sla_checks_count($candidate_id)
    {
            $items = 0;
            $data = DB::table('job_items')  
                    ->select('sla_id')        
                    ->where(['candidate_id'=>$candidate_id])
                    ->first();

            $items = DB::table('customer_sla_items')           
                    ->where(['sla_id'=>$data->sla_id])
                    ->count();

        return $items;
    }

     /*
    * @return mix
    */
    public static function get_total_sla_checks_count($candidate_id)
    {
            $items = 0;
            $items = DB::table('job_sla_items')        
                    ->where(['candidate_id'=>$candidate_id])
                    ->count();

        return $items;
    }

    /*
    * @return mix
    */
    public static function get_candidate_check_completed_count($candidate_id)
    {
            // $items = 0;
            $items = DB::table('jaf_form_data')       
                    ->where(['candidate_id'=>$candidate_id])
                    ->where('verification_status','=','success')
                    ->get();
        return $items;
    }

     /*
    * @return check jaf hold data from Candidate_hold_status table
    */
    public static function check_jaf_hold($candidate_id)
    {
            // $items = 0;
            $items = DB::table('candidate_hold_statuses') 
                    ->select('hold_by')      
                    ->where(['candidate_id'=>$candidate_id])
                    ->where('hold_remove_by','=',null)
                    ->first();
                    // dd($items);
        return $items;
    }
    /*
    * @return mix
    */
    public static function get_candidate_check_pending_count($candidate_id)
    {
            // $items = 0;
            $items = DB::table('jaf_form_data')       
                    ->where(['candidate_id'=>$candidate_id])
                    ->where('verification_status','!=','success')
                    ->get();

        return $items;
    }

   

    /*
    * @return mix
    */
    public static function get_approval_status_color_code($report_id)
    {
        $res_data = "";
        $data = DB::table('reports')
                    ->select('approval_status_id')            
                    ->where(['id'=>$report_id])
                    ->first();
            if($data !=null){
                $status = DB::table('report_status_masters')
                        ->select('color_name','color_code')            
                        ->where(['id'=>$data->approval_status_id])
                        ->first();
                $res_data =  $status->color_code;
            }
        return $res_data;
    }

    /*
    * @return mix
    */
    public static function get_sla_items(int $sla_id)
    {
        $res_data = "";
        
        $data = DB::table('customer_sla_items as sla')
                    ->select('s.name')    
                    ->join('services as s','s.id','=','sla.service_id')          
                    ->where(['sla.sla_id'=>$sla_id])
                    ->get();

        $count=count($data);
        foreach($data as $key => $item){
            if($count != $key+1)
                $res_data .= $item->name.', ';
            else
                $res_data .= $item->name;
        }
        return $res_data;
    }

     /*
    * @return mix
    */
    public static function get_vendor_sla_items(int $sla_id)
    {
        $res_data = "";

        // $service_id_set=explode(',',$sla_id);
        // $res_data = '';
        // $data = DB::table('services')
        //             ->select('name')            
        //             ->whereIn('id',$service_id_set)
        //             ->get();
        //     if(count($data)>0){
        //         foreach ($data as $key => $value) {
        //             $res_data .=' <span class="badge badge-secondary" style="padding: 5px;
        //             font-size: 11px;">' .$value->name .'</span>';
        //         }
                
        //     }
        
        $data = DB::table('vendor_sla_items as sla')
                    ->select('s.name')    
                    ->join('services as s','s.id','=','sla.service_id')          
                    ->where(['sla.sla_id'=>$sla_id])
                    ->get();

        foreach($data as $item){
            $res_data .= ' <span class="badge badge-secondary" style="padding: 5px;
                         font-size: 11px;">' . $item->name.'</span>';
        }
        return $res_data;
    }
    /*
    * @return mix
    */
    public static function get_sla_item_count($sla_id,$service_id)
    {
        $res_data = "";

        $data = DB::table('job_sla_items')
                    ->select('number_of_verifications')            
                    ->where(['job_id'=>$sla_id,'sla_item_id'=>$service_id])
                    ->first();
        
        return $data->number_of_verifications;
    }

    /*
    * @return mix
    */
    public static function get_sla_name($sla_id)
    {
        $res_data = "";
        $data = DB::table('customer_sla as sla')
                    ->select('sla.title')            
                    ->where(['sla.id'=>$sla_id])
                    ->first();

        if($data !=null){
            $res_data = $data->title;
        }
        return $res_data; 
    }

     /*
    * @return mix
     Get Sla name by Job_sla_item_id
    */
    public static function sla_name($job_sla_item_id)
    {
        $res_data = "";
        $sla_id =  DB::table('job_sla_items as jsi')
                    ->select('jsi.sla_id')            
                    ->where(['id'=>$job_sla_item_id])
                    ->first();
        if ($sla_id != null) {
            # code...
                 
            $data = DB::table('customer_sla as sla')
                    ->select('sla.title')            
                    ->where(['sla.id'=>$sla_id->sla_id])
                    ->first();

            if($data !=null){
                $res_data = $data->title;
            }
        }   
        return $res_data;
    }


    /*
    * @return mix
    */
    public static function get_vendor_sla_name($sla_id)
    {
        $res_data = "";
        $data = DB::table('vendor_slas as sla')
                    ->select('sla.title')            
                    ->where(['sla.id'=>$sla_id])
                    ->first();

        if($data !=null){
            $res_data = $data->title;
        }
        return $res_data;
    }

    public static function get_sla_tat($sla_id)
    {
        $res_data = "";
        $data = DB::table('customer_sla as sla')
                    ->select('sla.tat','sla.client_tat')            
                    ->where(['sla.id'=>$sla_id])
                    ->first();

        if($data !=null){
            $res_data = array('tat'=>$data->tat,'client_tat'=>$data->client_tat);
        }
        return $res_data;
    }

    public static function get_vendor_sla_tat($sla_id)
    {
        $res_data = "";
        $data = DB::table('vendor_slas as sla')
                    ->select('sla.tat')            
                    ->where(['sla.id'=>$sla_id])
                    ->first();

        if($data !=null){
            $res_data = array('tat'=>$data->tat);
        }
        return $res_data;
    }

    public static function get_sla_item_inputs(int $service_id)
    {
        $res_data = "";
        $data = DB::table('service_form_inputs as sfi')
                    ->select('sfi.*','s.name','s.type_name')
                    //->leftJoin('service_form_variable_inputs as sfvi','sfvi.service_form_input_id','=','sfi.id')
                    ->join('services as s','s.id','=','sfi.service_id')            
                    ->where(['sfi.service_id'=>$service_id,'sfi.status'=>1])
                    ->get();
                // if ($service_id==22) {
                //     dd($data);
                // }
                    //
        return $data;
    }

    // public static function get_check_control($service_input_id,$customer_id)
    // {
    //     $res_data = "";
    //     $data =DB::table('check_control_masters')->where(['check_control_coc_id'=>$customer_id,'service_input_id'=>$service_input_id])->first();
    //     return $data;
    // }
    public static function get_check_item_inputs(int $service_id)
    {
        $res_data = "";
        $data = DB::table('service_form_inputs as sfi')
                    ->select('sfi.*','s.name','s.type_name')
                    ->join('services as s','s.id','=','sfi.service_id')            
                    ->where(['sfi.service_id'=>$service_id,'sfi.status'=>1])
                    ->whereNull('reference_type')
                    ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                    ->get();
        return $data;
    }
    public static function get_check_control($service_id,$customer_id)
    {
        $res_data = "";
        $data = DB::table('check_control_masters')
                    ->where(['check_control_coc_id'=>$customer_id,'service_id'=>$service_id])
                    ->first();
                    
        return $data;
    }
    public static function check_item_input($service_id,$id)
    {
        $res_data = "";
        $data = DB::table('check_control_masters as c')
                    ->select('c.*')
                    ->join('service_form_inputs as si','si.id','=','c.service_input_id')
                    ->where(['c.check_control_coc_id'=>$id,'c.service_input_id'=>$service_id])
                    ->first();
                    
        return $data;
    }
    public static function get_diff_days($candidate_id,$jsi_id)
    {
        $res_data = "";
        $days=0;
        $sla_id= DB::table('job_sla_items as jsi')
                ->select('jsi.sla_id')
                ->where(['jsi.id'=>$jsi_id])->first();
        if($sla_id !=null){
            $tat = DB::table('customer_sla as sla')
                        ->select('sla.tat')            
                        ->where(['sla.id'=>$sla_id->sla_id])
                        ->first();
        
            if($tat !=null){
                $res_data = $tat->tat;
                
            }
            
            $data = DB::table('task_assignments as ta')
                        ->select('ta.created_at')            
                        ->where(['ta.candidate_id'=>$candidate_id])
                        ->first();
            $created_date = Carbon::parse($data->created_at)->format('d-m-Y');
            $now = Carbon::now();
            $remain= $now->diffInDays($created_date);
        
            $days =($res_data - $remain);
        }
        return $days;
    }

    //Difference between  given tat days in task assignment table and current time  
    public static function get_diff($candidate_id)
    {
        // $res_data = "";
        // $sla_id= DB::table('job_sla_items as jsi')
        //         ->select('jsi.sla_id')
        //         ->where(['jsi.id'=>$jsi_id])->first();

        // $tat = DB::table('customer_sla as sla')
        //             ->select('sla.tat')            
        //             ->where(['sla.id'=>$sla_id->sla_id])
        //             ->first();

        // if($tat !=null){
        //     $res_data = $tat->tat;
        //     // dd($res_data);
        // }
        $days ="";
        $data = DB::table('task_assignments as ta')
                    ->select('ta.tat','ta.created_at')            
                    ->where(['ta.candidate_id'=>$candidate_id,'ta.status'=>'1'])
                    ->first();
                    // dd($data);

        if($data !=null){
            
            $created_date = Carbon::parse($data->created_at)->format('d-m-Y');
            $now = Carbon::now();
            $remain= $now->diffInDays($created_date);
        
            $days =($data->tat - $remain);
        }
        // dd($days);
        return $days;
    }

    public static function diff_days($candidate_id,$sla_id)
    {
        // $res_data = "";
        // $sla_id= DB::table('job_sla_items as jsi')
        //         ->select('jsi.sla_id')
        //         ->where(['jsi.id'=>$jsi_id])->first();

        $tat = DB::table('customer_sla as sla')
                    ->select('sla.tat')            
                    ->where(['sla.id'=>$sla_id])
                    ->first();

        if($tat !=null){
            $res_data = $tat->tat;
            // dd($res_data);
        }
        $data = DB::table('task_assignments as ta')
                    ->select('ta.created_at')            
                    ->where(['ta.candidate_id'=>$candidate_id])
                    ->first();
                    // dd($data);
        $created_date = Carbon::parse($data->created_at)->format('d-m-Y');
        $now = Carbon::now();
        $remain= $now->diffInDays($created_date);
    
        $days =($res_data - $remain);
        // dd($days);
        return $days;
    }

    
    //Get  ouver due data for vendor
    public static function get_vendor_overdue_days($business_id,$sla_id,$service_id,$candidate_id)
    {
        $days = 0;
        $res_data="";
        // $sla_id= DB::table('job_sla_items as jsi')
        //         ->select('jsi.sla_id')
        //         ->where(['jsi.id'=>$jsi_id])->first();

        $vendor_sla = DB::table('vendor_slas')->where('id',$sla_id)->first();
       
        $tat = DB::table('vendor_sla_items as sla')
                    ->select('sla.tat')            
                    ->where(['sla.sla_id'=>$sla_id,'sla.vendor_id'=>$vendor_sla->vendor_id,'sla.business_id'=>$business_id,'sla.service_id'=>$service_id])
                    ->first();
                    //dd($tat);
        if($tat !=null){
            $res_data = $tat->tat;
            //dd($res_data);
        }
        $data = DB::table('vendor_tasks as vt')
                    ->select('vt.assigned_at','vt.reassigned_at')            
                    ->where(['vt.candidate_id'=>$candidate_id,'vt.vendor_sla_id'=>$sla_id,'vt.business_id'=>$business_id,'vt.service_id'=>$service_id,'vt.status'=>'1'])
                    ->first();
                //dd($data);
        if ($data) {
            
            if($data->reassigned_at!=NULL){
                $created_date = Carbon::parse($data->reassigned_at)->format('d-m-Y');
            }else{
                
                $created_date = Carbon::parse($data->assigned_at)->format('d-m-Y');
                
            }
                //dd($created_date);
            $now = Carbon::now();
            $remain= $now->diffInDays($created_date);
        
            $days =($res_data - $remain);
        }
        //dd($days);
        return $days;
    }

    public static function tat_overdue($start_date,$tat)
    {
        if($start_date!=NULL)
        {
            Carbon::setWeekendDays([ Carbon::SUNDAY ]);
            $date=Carbon::parse($start_date);
            $no_of_days=$tat;
            if($tat == 0)
            {
                $no_of_days=7;
            }

            $end_date=$date->addWeekDays($no_of_days)->format('Y-m-d');

            $today_date=Carbon::now()->format('Y-m-d');
            // $today_date=Carbon::parse('2021-04-18')->format('Y-m-d');
            $today=Carbon::now();
            // $today=Carbon::parse('2021-04-18');
            if($today_date > $end_date)
            {
                return $today->diffInWeekDays($end_date)-1;
            }
            else
            {
                return 0;
            }

        }
        return 0;
    }
    
    public static function get_sla_item_input_type($form_input_type_id)
    {
        $res_data = "";
        $data = DB::table('form_input_masters')
                ->select('*')            
                ->where(['id'=>$form_input_type_id])
                ->first();
        if($data !=null){
            $res_data = $data->name;
        }   

        return $res_data; 
        
    }

    // get report output inputs
    public static function get_report_form_inputs(int $service_id)
    {
        $res_data = "";
        $data = DB::table('service_form_inputs as sfi')
                    ->select('sfi.*')            
                    ->where(['sfi.service_id'=>$service_id,'is_report_output'=>'1'])
                    ->get();
        return $data;
    }

    //verified_by
    public static function get_report_verified_by(int $service_id)
    {
        $res_data = "";
        $data = DB::table('report_items')
                    ->select('verified_by')            
                    ->where(['id'=>$service_id])
                    ->first();

        return $data->verified_by;
    }
    
    // comments
    public static function get_report_comments(int $service_id)
    {
        $res_data = "";
        $data = DB::table('report_items')
                    ->select('comments')            
                    ->where(['id'=>$service_id])
                    ->first();
                    
        return $data->comments;
    }

    // report status
    public static function get_report_status(int $candidate_id)
    {
        $res_data = NULL;
        $data = DB::table('reports')
                    ->select('status','id')            
                    ->where(['candidate_id'=>$candidate_id])
                    ->first();
        if($data !=null){
          $res_data['status'] =  $data->status;
          $res_data['id'] =  $data->id;
        }           
        return $res_data;
    }

    // report status
    public static function get_jaf_auto_check_api_status(int $candidate_id)
    {
        $res_data = NULL;
        $jaf_item = DB::table('jaf_form_data as jf')
        ->select('jf.is_data_verified')
        ->where(['jf.candidate_id'=>$candidate_id])
        ->get();
        $item = DB::table('jaf_form_data as jf')
        ->select('jf.is_data_verified')
        ->where(['jf.candidate_id'=>$candidate_id])
        ->count();
        $i=0;
       $j=0;
        foreach ($jaf_item as $value) {
            if ($value->is_data_verified=='1') {
                $i++;
            }
            $j++;
            // elseif ($value->is_data_verified=='1') {
            //     $j++;
            // } 
           
        }
        
        // ,'jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.is_api_checked','jf.verification_status','jf.verified_at'
        //  var_dump($jaf_item);
        if(count($jaf_item)>0){
            if($i<=0 ){
                $res_data = "<span class='badge badge-secondary rounded-circle' title='Under Process...' style='width:20px; height:20px;'>".$i."</span>  out of <strong>".$j."</strong> check is verified";
                // $res_data = "<small class='text-warning'> Under Process...</small>";
            }else if($i>0 && $i<$item){
                if ($i==1) {
                    $check = "check is";
                }
                else {
                    $check = "checks are";
                }
                $res_data = "<span class='badge badge-warning rounded-circle' title='Partially Verified' style='width:20px; height:20px;'>".$i."</span> out of <strong>".$j."</strong> " .$check." verified";
                // $res_data =  "<small class='text-info'>Partially Verified</small>";
            }
            else {
                if ($i==1) {
                    $check = "check is";
                }
                else {
                    $check = "checks are";
                }
                $res_data = "<span class='badge badge-success rounded-circle' title='Verified' style='width:20px; height:20px;'>".$i."</span> out of <strong>".$j."</strong> " .$check." verified";
                // $res_data =  "<small class='text-success'>Verified</small>";    ;
    
            }
        }
        else{
            $res_data = "<small class='text-danger'> Waiting for JAF Data </small>";
        }           
        return $res_data;
    }

    
    // additional comments
    public static function get_report_additional_comments(int $service_id)
    {
        $res_data = "";
        $data = DB::table('report_items')
                    ->select('additional_comments')
                    ->where(['id'=>$service_id])
                    ->first();
                    
        return $data->additional_comments;
    }

    /*
    * @return mix
    */
    public static function get_single_data($table,$col,$col_name,$col_val)
    {
        $data = DB::table($table)
                    ->select($col)             
                    ->where([$col_name=>$col_val])
                    ->first();
        $res_data="";
        
        if($data != null){
            $res_data= $data->$col;    
        }
        return $res_data;
        
    }

    /*
    * @return mix
    */
    public static function get_user_type($business_id)
    {
        $account_type = '';
        $account_type = DB::table('users')->select('business_id','user_type','parent_id','is_business_data_completed')->where(['business_id'=>$business_id])->first();        
       
        if($account_type !=null){
           $account_type = $account_type->user_type;
        }
        return $account_type;
    }

   
    /*
    * @return mix
    */
    public static function get_role_name($role_id)
    {   $role_name = "";
        $role = DB::table('role_masters')->select('id','role')->where(['id'=>$role_id])->first();  
        
        if($role !=null){      
            $role_name = $role->role;
        }

        return $role_name;
    }

    /*
    * @return mix
    */
    public static function get_user_fullname($user_id)
    {   $res = '';
        $data = DB::table('users')->select('id','first_name','last_name')->where(['id'=>$user_id])->first();
        if($data !=null){
         $res = $data->first_name.' '.$data->last_name;
        }
        return $res;
    }

    public static function get_candidate_name_fullname($user_id)
    {   $res = '';
        $data = DB::table('candidate_reinitiates')->select('id','name','first_name','last_name')->where(['id'=>$user_id])->first();
        if($data !=null){
         $res = $data->first_name.' '.$data->last_name;
        }
        return $res;
    }

     /**
     * @param char $file_name, 
     * 
     * @return array result
     */
    public static function getFilePrev($file_name,$file_path) {         
       
        $type = url('/').'/images/file.jpg';
        $extArray = explode('.', $file_name);
        $ext = end($extArray);           
       
            if($ext == 'pdf')
            {
              $type = url('/').'/images/icon_pdf.png';
            }
            if($ext == 'psd')
            {
              $type = url('/').'/images/icon_psd.png';
            } 
            if($ext == 'doc' || $ext == 'docx')
            {
              $type = url('/').'/images/icon_docx.png';
            }
            if($ext == 'xlsx' || $ext == 'csv' || $ext == 'xls')
            {
              $type = url('/').'/images/icon_xlsx.png';
            }
            if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
            {              
              $type = url('/').'/upload/'.$file_path.$file_name;
            }
            if($ext == 'pptx' || $ext == 'ppt')
            {
              $type = url('/').'/images/icon_pptx.png';
            }

        return $type;
    }
    
     /**
     * @param int $sla_id, 
     * 
     * @return array result
     */
    public static function getReportAttachFiles($item_id,$attachment_type) {
        
        $reportFiles = DB::table('report_item_attachments as rf')
                        ->select('rf.id','rf.file_name','rf.created_at','rf.attachment_type','rf.file_platform')                        
                        ->where(['rf.report_item_id'=>$item_id,'rf.is_deleted'=>0,'rf.attachment_type'=>$attachment_type])  
                        ->orderBy('rf.img_order','ASC')  
                        ->get();    
                            
        $path = public_path().'/uploads/report-files/';
        $docs = array();
        foreach ($reportFiles as $item) {

            $type = url('/').'/images/icon_docx.png';
            $extArray = explode('.', $item->file_name);
            $ext = end($extArray);
           
            if($item->file_name != NULL)
            {
                if($ext == 'pdf')
                {
                  $type = url('/').'/admin/images/icon_pdf.png';
                } 
                if($ext == 'zip')
                {
                  $type = url('/').'/admin/images/zip.png';
                } 
                if($ext == 'doc' || $ext == 'docx')
                {
                  $type = url('/').'/admin/images/icon_docx.png';
                }
                if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                {
                  $type = url('/').'/admin/images/icon_xlsx.png';
                }
                if($ext == 'jpg' || $ext == 'png' || $ext == 'zip'|| $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                {  
                    if(stripos($item->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/report-files/';

                        $filename = $item->file_name;

                        $s3_config = S3ConfigTrait::s3Config();

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$filename,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $type = (string)$req->getUri();
                    }
                    else
                    {
                        $type = url('/').'/uploads/report-files/'.$item->file_name;
                    }
                  //$type = url('/').'/admin/upload/project_doc/'.$item->file_name;
                }
                if($ext == 'pptx')
                {
                  $type = url('/').'/admin/images/icon_pptx.png';
                }

            }

            $docs[]= array(
                'file_id'=>$item->id, 
                'file_name'=>$item->file_name,               
                'attachment_type'=>$item->attachment_type, 
                'filePath'=>url('/').'/uploads/report-files/'.$item->file_name,
                'fileIcon'=>$type,
            );
         } 

        //  dd($docs);
        return $docs;
    }


     /**
     * @param int $sla_id, 
     * 
     * @return array result
     */
    public static function getJAFAttachFiles($item_id) {
        
        $reportFiles = DB::table('jaf_item_attachments as jf')
        ->select('jf.id','jf.jaf_id','jf.file_name','jf.created_at','jf.attachment_type','jf.file_platform')                        
        ->where(['jf.jaf_id'=>$item_id,'is_deleted'=>'0']) 
        ->orderBy('img_order','ASC')   
        ->get();     
        
            // dd($reportFiles);
        $docs = array();
        foreach ($reportFiles as $item) {

            $type = url('/').'/admin/images/icon_docx.png';
            $extArray = explode('.', $item->file_name);
            $ext = end($extArray);
           
            if($item->file_name != NULL)
            {
                if($ext == 'pdf')
                {
                  $type = url('/').'/admin/images/icon_pdf.png';
                } 
                if($ext == 'doc' || $ext == 'docx')
                {
                  $type = url('/').'/admin/images/icon_docx.png';
                }
                if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                {
                  $type = url('/').'/admin/images/icon_xlsx.png';
                }
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                {  
                  if(stripos($item->file_platform,'s3')!==false)
                  {
                    $filePath = 'uploads/jaf-files/';

                    $filename = $item->file_name;

                    $s3_config = S3ConfigTrait::s3Config();

                    $disk = Storage::disk('s3');

                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                        'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                        'Key'                        => $filePath.$filename,
                        'ResponseContentDisposition' => 'attachment;'//for download
                    ]);

                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                    $type = (string)$req->getUri();
                  }
                  else
                  {
                    $type = url('/').'/uploads/jaf-files/'.$item->file_name;
                  }
                }
                 if($ext == 'pptx')
                {
                  $type = url('/').'/admin/images/icon_pptx.png';
                }

            }            

            $docs[]= array(
                'file_id'=>$item->id, 
                'file_name'=>$item->file_name,               
                'attachment_type'=>$item->attachment_type, 
                'filePath'=>url('/').'/uploads/jaf-files/'.$item->file_name,
                'fileIcon'=>$type,
            );
         } 

        return $docs;
    }

    /**
     * @param int $project _id, 
     * 
     * @return array result
     */
    public static function getCustomerFilePrev($file_id) {         
       
        $user_business_attachment = DB::table('user_business_attachments')->where('id',$file_id)->first();

        $file_name = $user_business_attachment->file_name;
        $type = url('/').'/admin/images/file.jpg';
        $extArray = explode('.', $file_name);
        $ext = end($extArray);           
       
            if($ext == 'pdf')
            {
              $type = url('/').'/admin/images/icon_pdf.png';
            } 
            if($ext == 'doc' || $ext == 'docx')
            {
              $type = url('/').'/admin/images/icon_docx.png';
            }
            if($ext == 'xlsx' || $ext == 'csv' || $ext == 'xls')
            {
              $type = url('/').'/admin/images/icon_xlsx.png';
            }
            if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
            {   
                if(stripos($user_business_attachment->file_platform,'s3')!==false)
                {
                    $filePath = 'uploads/customer-files/';

                    $s3_config = S3ConfigTrait::s3Config();

                    $disk = Storage::disk('s3');

                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                    ]);

                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                    $type = (string)$req->getUri();
                }
                else
                {
                    $type = url('/').'/uploads/customer-files/'.$file_name;
                }           
            }
            if($ext == 'pptx' || $ext == 'ppt')
            {
              $type = url('/').'/images/icon_pptx.png';
            }

        return $type;
    }

  /**
     * @param int $project _id, 
     * 
     * @return array result
     */
    public static function getVendorFilePrev($file_id) {         
       
        $vendor_business_attachment = DB::table('vendor_business_attachments')->where('id',$file_id)->first();

        $file_name = $vendor_business_attachment->file_name;
        $type = url('/').'/admin/images/file.jpg';
        $extArray = explode('.', $file_name);
        $ext = end($extArray);           
       
            if($ext == 'pdf')
            {
              $type = url('/').'/admin/images/icon_pdf.png';
            } 
            if($ext == 'doc' || $ext == 'docx')
            {
              $type = url('/').'/admin/images/icon_docx.png';
            }
            if($ext == 'xlsx' || $ext == 'csv' || $ext == 'xls')
            {
              $type = url('/').'/admin/images/icon_xlsx.png';
            }
            if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
            { 
                if(stripos($vendor_business_attachment->file_platform,'s3')!==false)
                {
                    $filePath = 'uploads/vendor-files/';

                    $s3_config = S3ConfigTrait::s3Config();

                    $disk = Storage::disk('s3');

                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                    ]);

                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                    $type = (string)$req->getUri();
                }
                else
                {
                    $type = url('/').'/uploads/vendor-files/'.$file_name;
                }        

            }
            if($ext == 'pptx' || $ext == 'ppt')
            {
              $type = url('/').'/images/icon_pptx.png';
            }

        return $type;
    }

/*
    * @return mix
    */
    public static function get_service_items($user_id)
    {
        $res_data = "";
        // dd($user_id);
        $data = DB::table('user_checks as check')
                    ->select('s.name')    
                    ->join('services as s','s.id','=','check.checks')          
                    ->where(['check.user_id'=>$user_id])
                    ->get();
        // print_r($data);
        foreach($data as $item){
            $res_data .= ' <span class="badge badge-secondary" style="padding: 5px;
            font-size: 11px;">' .$item->name .'</span>';
        }
        return $res_data;
    }


    
/*
    * @return mix
    */
    public static function get_job_sla_items(int $service_id)
    {
        $res_data = "";
        
        $data = DB::table('user_checks as check')
                    ->select('jb.job_id','jb.service_id','check.checks')    
                    ->join('job_sla_items as jb','jb.service_id','=','check.checks')          
                    ->where(['check.checks'=>$service_id])
                    ->get();
        // dd($data);
        foreach($data as $item){
            $res_data .= $item->name.', ';
        }
        // $fresh_data =rtrim($res_data,',')
        return $res_data;
    }

    /**
     * @param int $project _id, 
     * 
     * @return array result
     */
    
    public static function array_flatten1($array) { 
    if (!is_array($array)) { 
      return FALSE; 
    }  
    $result = array(); 
    foreach ($array as $key => $value) { 
      if (is_array($value)) { 
        $result = array_merge($result, array_flatten($value));  
      } 
      else { 
        $result[$key] = $value; 
      } 
    } 
    return $result;  
  } 

  public static function get_user_permission($role,$business_id){
    
        $permission=  RoleMaster::where('id',$role)->where('business_id',$business_id)->first();
        // dd($permission);
        $action = $permission->permission->permission_id;
        // dd($action);
        return $action;
    
  }

   public static function get_graph_data($type,$business_id)
   {
    $services = DB::table('services')
    ->select('name','id')
    ->where(['status'=>$type])
    ->where('business_id',NULL)
    ->whereNotIn('type_name',['e_court','gstin'])
    ->orwhere('business_id',$business_id)
    ->get();

    $array_result = [];

    foreach ($services as $key => $value) {
        
        $completed = DB::table('jaf_form_data as jf')
        ->DISTINCT('u.id')
        ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
        ->where(['jf.service_id'=> $value->id, 'jf.business_id'=>Auth::user()->business_id,'jf.verification_status'=>'success'])
        ->count();

        $pending = DB::table('jaf_form_data as jf')
        ->DISTINCT('u.id')
        ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
        ->where(['jf.service_id'=> $value->id, 'jf.business_id'=>Auth::user()->business_id,'jf.verification_status'=>null])
        ->count();

        $array_result[] = ['check_name'=> $value->name, 'completed'=>$completed, 'pending'=> $pending]; 
        // dd($array_result);
        
    }
    return $array_result;

   }

   public static function get_graph_admin_data($type,$business_id)
   {
    $services = DB::table('services')
    ->select('name','id')
    ->where(['status'=>$type])
    ->where('business_id',NULL)
    ->whereNotIn('type_name',['e_court','gstin'])
    ->orwhere('business_id',$business_id)
    ->get();

    $array_result = [];

    foreach ($services as $key => $value) {
        
        $completed = DB::table('jaf_form_data as jf')
        ->DISTINCT('u.id')
        ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
        ->where(['jf.service_id'=> $value->id, 'u.parent_id'=>Auth::user()->business_id,'jf.verification_status'=>'success'])
        ->count();

        $pending = DB::table('jaf_form_data as jf')
        ->DISTINCT('u.id')
        ->join('candidate_reinitiates as u','u.id','=','jf.candidate_id')
        ->where(['jf.service_id'=> $value->id, 'u.parent_id'=>Auth::user()->business_id,'jf.verification_status'=>null])
        ->count();

        $array_result[] = ['check_name'=> $value->name, 'completed'=>$completed, 'pending'=> $pending]; 
        // dd($array_result);
        
    }
    return $array_result;

   }
//   function check_user_permission($parent_id,$route_group=''){
//     if($route_group){
//       $condit= [
//               'parent_id' => $parent_id,
//                'route_group'=>$route_group,
//             ];
//       }else{
//         $condit= [
//                 'parent_id' => $parent_id,
//               ];
//        }
//       $permission=  ActionMaster::where($condit)->get();
//       return $permission;
//   }

    public static function get_page_permission($parent_id,$route_group=''){

        if($route_group){
        $condit= [
                'parent_id' => $parent_id,
                'route_group'=>$route_group,
            ];
        }else{
        $condit= [
                'parent_id' => $parent_id,
                ];
        }
    $roles= ActionMaster::where($condit)->get();
    return $roles;
    } 


    public static function can_access($route,$route_group){
        $business_id   = Auth::user()->business_id;
        $user_type  = Auth::user()->user_type;
        $role_id    = Auth::user()->role;
        if(($user_type == 'customer'|| $user_type == 'client') && ($role_id=='' || $role_id==0)){
        return true;
        }else{
        $action = DB::table('action_masters')->select('id')->where(['action_title'=>$route,'route_group'=>$route_group])->first();
         // dd($action);
        $role_id_arr=explode(",",$role_id);
        $count = DB::table('role_permissions')->where(['business_id'=>$business_id,'status'=>'1'])->whereIn('role_id',explode(",",$role_id))->count();
            if($count>0){
            $access_data = DB::table('role_permissions')->select('permission_id')->where(['business_id'=>$business_id,'status'=>'1'])->whereIn('role_id',explode(",",$role_id))->get();
            foreach($access_data as $access){
                foreach(json_decode($access->permission_id) as $action_list_id){
                    // dd($action);
                if($action->id==$action_list_id){
                    return true;
                }
                }
            }
            }else{
                return false;
            }
        }
    }

    public static function primary_kam_list($business_id)
    {
        //
        $data=DB::table('key_account_managers as k')
                ->select('u.name','u.phone')
                ->join('users as u','u.id','=','k.user_id')
                ->where(['k.business_id'=>$business_id,'k.is_primary'=>'1'])
                ->first();
        return $data;

    }

    public static function secondary_kam_list($business_id)
    {
        //
        $data=DB::table('key_account_managers as k')
                ->select('u.name','u.phone')
                ->join('users as u','u.id','=','k.user_id')
                ->where(['k.business_id'=>$business_id,'k.is_primary'=>'0'])
                ->first();
        return $data;
    }


    public static function get_jaf_form_data($candidate_id,$service_id,$check_items)
    {
        //
        $data=DB::table('jaf_form_data as jf')
                ->select('jf.*','s.name','s.verification_type','s.type_name')
                ->join('services as s','jf.service_id','=','s.id')
                ->where(['jf.candidate_id'=>$candidate_id,'jf.service_id'=>$service_id,'jf.check_item_number'=>$check_items])
                ->first();

                
        return $data;
    }

    public static function get_is_executive_summary($service_id,$label_name)
    {
        //
        $data=DB::table('service_form_inputs')
                ->select('is_executive_summary')
                ->where(['service_id'=>$service_id,'label_name'=>$label_name])
                ->first();

                // print_r($data);
                
        return $data;
    }

    //check price for COC Panel
    public static function get_check_coc_price($service_id)
    {
        $business_id=Auth::user()->business_id;
        $parent_id=Auth::user()->parent_id;
        
        $price=NULL;
        
        $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
        if($data!=NULL)
        {
            $price=$data->price;
        }
        else{
            $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            // else{
            //     $data=DB::table('check_price_masters')->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
            //     if($data!=NULL)
            //     {
            //         $price=$data->price;
            //     }
            // }
        }

        return $price;

    }

    //check price for COC
    public static function get_check_coc_wise_price($service_id,$business_id,$parent_id)
    {
        $price=NULL;
        
        $data = DB::table('check_price_cocs')->where(['service_id'=>$service_id,'coc_id'=>$business_id])->first();
        if($data!=NULL)
        {
            $price=$data->price;
        }
        else{
            $data=DB::table('check_prices')->where(['service_id'=>$service_id,'business_id'=>$parent_id])->first();
            if($data!=NULL)
            {
                $price=$data->price;
            }
            // else{
            //     $data=DB::table('check_price_masters')->where(['business_id'=>$parent_id,'service_id'=>$service_id])->first();
            //     if($data!=NULL)
            //     {
            //         $price=$data->price;
            //     }
            // }
        }

        return $price;
    }

    public static function get_jaf_status($job_item_id)
    {
        $job_items=DB::table('job_items')->where('id',$job_item_id)->first();

        $status=NULL;

        if($job_items!=NULL)
        {
            $status=$job_items->jaf_status;
        }

        return $status;
    }

    public static function get_business_id($user_id)
    {
        $business_id=NULL;
        $users=DB::table('users')->where('id',$user_id)->first();

        if($users!=NULL)
        {
            $business_id=$users->business_id;
        }

        return $business_id;
    }

    public static function get_kam($user_id,$business_id)
    {
        // $business_id=NULL;
        $kam = DB::table('key_account_managers')->where(['user_id'=>$user_id,'business_id'=>$business_id])->first();
        // dd($kams);
        if($kam !=NULL)
        {
            return $kam;
        }

        
    }

    /*
    * @return check price show/hide data from customer_check_price_showing_statuses table
    */
    public static function check_price_show($id)
    {
            // $items = 0;
            $items = DB::table('customer_check_price_showing_statuses')       
                    ->where(['coc_id'=>$id])
                    ->where('shown_by','=',null)
                    ->first();
                    // dd($items);
        return $items;
    }

    public static function get_insuff_attachFile($jaf_id)
    {
        $attach=NULL;
        $ver_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$jaf_id,'status'=>'raised'])->first();
        if($ver_data!=NULL)
        {
            $attach=$ver_data->attachment;
        }
        return $attach;
    }

    public static function get_jaf_attachFile($candidate_id)
    {
        $attach=[];

        $file_name = '';

        $file_platform = '';
        // $attach = NULL;
        $ver_data=DB::table('jaf_files')->where(['candidate_id'=>$candidate_id])->get();
        // dd($ver_data);
        if(count($ver_data)>1)
        {
            foreach ($ver_data as $key => $data) {

            // $attach = $data->zip_file;

                $file_name = $data->zip_file;

                $file_platform = $data->file_platform;
            
             }

             $attach['file_name']=$file_name;

             $attach['file_platform']=$file_platform;

        }elseif (count($ver_data)==1) {

          foreach ($ver_data as $key => $data) {

            $file_name = $data->file_name;

            $file_platform = $data->file_platform;
            //$attach=$data->file_name;
            // $extension = pathinfo($attach, PATHINFO_EXTENSION);
            // dd($extension);
             }

             $attach['file_name']=$file_name;

             $attach['file_platform']=$file_platform;
        }

        return $attach;
    }
    /*
    * @return Verification show/hide data from customer_verification_showing_statuses table
    */
    public static function verification_show($id)
    {
            // $items = 0;
            $items = DB::table('customer_verification_showing_statuses')
                    ->where(['coc_id'=>$id])
                    ->where('shown_by','=',null)
                    ->first();
                    // dd($items);
        return $items;
    }

    public static function service_control_show($id)
    {
            // $items = 0;
            $items = DB::table('check_coc_controls')
                    ->where(['coc_id'=>$id])
                    ->where('shown_by','=',null)
                    ->first();
                    // dd($items);
        return $items;
    }
    public static function report_show($id,$type)
    {
            // $items = 0;
            $items = DB::table('report_add_page_statuses')
                    ->select('status')
                    ->where(['coc_id'=>$id,'template_type'=>$type])
                    ->first();
                    // dd($items);
        return $items;
    }
    //Report custom
    public static function report_custom($id)
    {
            // $items = 0;
            $status =NULL;
            $items = DB::table('report_custom_pages')
                    ->select('status')
                    ->where(['coc_id'=>$id])
                    ->first();
                    // dd($items);
            if ($items) {
                $status = $items->status;
                return $status;
            }
            else {
                return $status;
            }
       
    }

        //Report custom
        public static function report_address_custom($id)
        {
                // $items = 0;
                $status =NULL;
                $items = DB::table('customer_p_d_f_addresses')
                        ->select('status')
                        ->where(['business_id'=>$id])
                        ->first();
                        // dd($items);
                if ($items!=null) {
                    $status = $items->status;
                    return $status;
                }
                else {
                    return $status;
                }
            
        }
    /*customer_p_d_f_addresses
    * @return Verification Service show/hide data from customer_verification_service_showing_statuses table
    */
    public static function verification_service_show($id,$service_id)
    {
            // $items = 0;
            $items = DB::table('customer_verification_service_showing_statuses')       
                    ->where(['coc_id'=>$id,'service_id'=>$service_id])
                    ->where('shown_by','=',null)
                    ->first();
                    // dd($items);
            return $items;
    }

    public static function get_verification_terms($business_id)
    {
        $data=NULL;
        $ver=DB::table('verification_term_logs')->where(['business_id' => $business_id])->first();
        if($ver!=NULL)
        {
            $data=$ver;
        }

        return $data;

    }

    public static function get_check_price_master_admin_data($parent_id,$business_id,$service_id)
    {
        $data=NULL;
        $check_price_master=DB::table('check_price_masters')
                        ->where('service_id',$service_id)
                        ->where('business_id',$parent_id)
                        // ->orwhere('business_id',$business_id)
                        ->first();
        // if($service_id==37)
        // {
        //     dd($service_id);
        // }
        if($check_price_master!=NULL)
        {
            $data=$check_price_master;
        }

        return $data;
    }

    public static function get_check_price_coc_global_data($business_id,$service_id)
    {
        $data=NULL;
        $check_price_coc=DB::table('check_prices')->where(['business_id'=>$business_id,'service_id'=>$service_id])->first();
        if($check_price_coc!=NULL)
        {
            $data=$check_price_coc;
        }

        return $data;
    }

    // get Job item data
    public static function check_jaf_item($candidate_id,$business_id)
    {
        $data=NULL;
        $job_item=DB::table('job_items')->where(['business_id'=>$business_id,'candidate_id'=>$candidate_id])->whereIn('jaf_status',['pending','draft'])->first();
        if($job_item!=NULL)
        {
            $data=$job_item;
        }

        return $data;
    }

    public static function get_guest_verification_services($guest_v_id)
    {
        $res_data=NULL;
        $data = DB::table('guest_verification_services')            
                    ->where('gv_id',$guest_v_id)
                    ->get();
                    
        if(count($data)>0)
        {
            $res_data=$data;
        }
        return $res_data;
    }

    public static function get_guest_order_service_name($guest_v_id)
    {
        // $service_id_set=explode(',',$service_id);
        $res_data = '';
        $data = DB::table('guest_verification_services')            
                    ->where('gv_id',$guest_v_id)
                    ->get();
            $count=count($data);
            $i=0;
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    $services=DB::table('services')->where('id',$value->service_id)->first();
                    if(++$i==$count)
                        $res_data .= $services->name;
                    else
                        $res_data .= $services->name .', ';
                }
                
            }
        return $res_data;
    }

    public static function get_guest_instant_order_service_name($guest_master_id)
    {
        // $service_id_set=explode(',',$service_id);
        $res_data = '';
        $data = DB::table('guest_instant_carts')            
                    ->where('giv_m_id',$guest_master_id)
                    ->get();
            $count=count($data);
            $i=0;
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    $services=DB::table('services')->where('id',$value->service_id)->first();
                    if(++$i==$count)
                        $res_data .= $services->name;
                    else
                        $res_data .= $services->name .', ';
                }
                
            }
        return $res_data;
    }

    public static function get_guest_order_report_pdf($guest_v_id)
    {
        $file_name=NULL;
        $data = DB::table('guest_verification_services')            
                    ->where('gv_id',$guest_v_id)
                    ->get();

        if(count($data)==1)
        {
            foreach($data as $key=>$value)
            {
                $file_name=$value->file_name;
            }
        }

        return $file_name;
    }

    public static function get_report_page($coc_id)
    {
        $data=NULL;
        $report=DB::table('report_add_page_statuses')->where(['coc_id' => $coc_id,'template_type'=>'2'])->first();
        if($report!=NULL)
        {
            $data=$report;
        }

        return $data;

    }
    
    public static function get_registered_company_addr($business_id)
    {
        $data=NULL;
        $addr=DB::table('user_businesses')->where(['business_id' => $business_id])->first();
        if($addr!=NULL)
        {
            $data=$addr->address_line1 . '-'.$addr->zipcode;
        }

        return $data;

    }
    
    public static function get_raise_service_name_slot($jaf_id,$candidate_id,$service_id,$ver_status='')
    {
        $service_id_set=explode(',',$service_id);
        $jaf_id_set=explode(',',$jaf_id);
        $res_data = '';
        $i=0;

        $data = DB::table('jaf_form_data as jf')
                    ->select('jf.*','v.status','v.notes','v.created_at','v.updated_at')
                    ->join('verification_insufficiency as v','jf.id','=','v.jaf_form_data_id')
                    ->whereIn('jf.id',$jaf_id_set);
                    if($ver_status!='')
                    {
                        $data->where('v.status',$ver_status);
                    }
        $data=$data->orderBy('jf.service_id')
                    ->orderBy('jf.check_item_number')
                    ->get();
    
        if(count($data)>0)
        {
            foreach($data as $value)
            {
                $services=DB::table('services')->where(['id'=>$value->service_id])->first();

                if($value->status=='raised')
                {
                    if($value->updated_at==NULL)
                        $insuff_date=$value->created_at;
                    else
                        $insuff_date=$value->updated_at;
                    

                    if($services->verification_type=='Manual')
                        $res_data .="<span class='badge badge-danger raise_detail m-1' data-jaf='".base64_encode($value->id)."' data-candidate='".base64_encode($candidate_id)."' data-service='".base64_encode($value->service_id)."' data-service_name='$services->name' data-notes='$value->notes' style='padding: 5px;
                            font-size: 11px;cursor:pointer;'>" .$services->name .' - '.$value->check_item_number . "<br>".date('d-m-Y h:i A',strtotime($insuff_date))."</span>";
                    else
                        $res_data .=" <span class='badge badge-danger raise_detail m-1' data-jaf='".base64_encode($value->id)."' data-candidate='".base64_encode($candidate_id)."' data-service='".base64_encode($value->service_id)."' data-service_name='$services->name' data-notes='$value->notes' style='padding: 5px;
                        font-size: 11px;cursor:pointer;'>" .$services->name ."<br>".date('d-m-Y h:i A',strtotime($insuff_date))."</span>";
                }
                else if($value->status=='removed')
                {
                    if($value->updated_at==NULL)
                        $insuff_date=$value->created_at;
                    else
                        $insuff_date=$value->updated_at;

                    if($services->verification_type=='Manual')
                        $res_data .=" <span class='badge badge-success clear_detail m-1' data-jaf='".base64_encode($value->id)."' data-candidate='".base64_encode($candidate_id)."' data-service='".base64_encode($value->service_id)."' data-service_name='$services->name' data-notes='$value->notes' style='padding: 5px;
                        font-size: 11px;cursor:pointer;'>" .$services->name. ' - '.$value->check_item_number."<br>".date('d-m-Y h:i A',strtotime($insuff_date))."</span>";
                    else
                        $res_data .=" <span class='badge badge-success clear_detail m-1' data-jaf='".base64_encode($value->id)."' data-candidate='".base64_encode($candidate_id)."' data-service='".base64_encode($value->service_id)."' data-service_name='$services->name' data-notes='$value->notes' style='padding: 5px;
                        font-size: 11px;cursor:pointer;'>" .$services->name."<br>".date('d-m-Y h:i A',strtotime($insuff_date))."</span>";
                }
            }
        }
        
        return $res_data;
    }

    public static function get_report_data($jaf_id,$service_id,$service_number)
    {
        $report_data = DB::table('report_items')->where(['jaf_id'=>$jaf_id,'service_id'=>$service_id,'service_item_number'=>$service_number])->first();
     
       
                    // dd($report_data);
        return $report_data;
    }

    public static function api_details($business_id,$service_id,$type,$condition=true)
    {
        $data=NULL;
        $services = DB::table('services')->where(['id'=>$service_id])->first();
        if($service_id=='2')
        {
            $data=DB::table('aadhar_checks as a')
            ->select('s.name','a.aadhar_number','a.user_id','a.created_at','a.price')
            ->join('services as s','s.id','=','a.service_id')
            ->where(['a.source_reference'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
            if($condition)
            {
                $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                        ->whereDate('a.created_at','<=',date('Y-m-d'));
            }
            $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif($service_id=='3')
        {
            $data=DB::table('pan_checks as a')
                ->select('s.name','a.pan_number','a.user_id','a.created_at','a.price','a.full_name')
                ->join('services as s','s.id','=','a.service_id')
                ->where(['a.source_type'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                if($condition)
                {
                    $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                }
                $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif($service_id=='4')
        {
            $data=DB::table('voter_id_checks as a')
                    ->select('s.name','a.voter_id_number','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_reference'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                                ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                    $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif($service_id=='7')
        {
            $data=DB::table('rc_checks as a')
                ->select('s.name','a.rc_number','a.user_id','a.created_at','a.price','a.owner_name')
                ->join('services as s','s.id','=','a.service_id')
                ->where(['a.source_type'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                if($condition)
                {
                    $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                }
                $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif($service_id=='9')
        {
            $data=DB::table('dl_checks as a')
                    ->select('s.name','a.dl_number','a.user_id','a.created_at','a.price','a.name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                                ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                    $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif($service_id=='8')
        {
            $data=DB::table('passport_checks as a')
                    ->select('s.name','a.passport_number','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                   $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif($service_id=='12')
        {
            $data=DB::table('bank_account_checks as a')
                    ->select('s.name','a.account_number','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                    $data = $data->orderBy('a.id','desc')
                    ->get();

        }
        elseif($service_id=='14')
        {
            $data=DB::table('gst_checks as a')
                ->select('s.name','a.gst_number','a.user_id','a.created_at','a.price','a.legal_name')
                ->join('services as s','s.id','=','a.service_id')
                ->where(['a.source_type'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                if($condition)
                {
                    $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                        ->whereDate('a.created_at','<=',date('Y-m-d'));
                }
                $data = $data->orderBy('a.id','desc')
                ->get();
        }
        elseif($service_id=='19')
        {
            $data=DB::table('telecom_check as a')
                    ->select('s.name','a.mobile_no','a.user_id','a.created_at','a.price','a.full_name')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.used_by'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                    $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif(stripos($services->type_name,'e_court')!==false)
        {
            $data=DB::table('e_court_checks as a')
                    ->select('s.name as service_name','a.name','a.father_name','a.address','a.user_id','a.created_at','a.price')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                    $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif(stripos($services->type_name,'upi')!==false)
        {
            $data=DB::table('upi_checks as a')
                    ->select('s.name as service_name','a.name','a.upi_id','a.user_id','a.created_at','a.price')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                    $data = $data->orderBy('a.id','desc')
                    ->get();
        }
        elseif(stripos($services->type_name,'cin')!==false)
        {
            $data=DB::table('cin_checks as a')
                    ->select('s.name as service_name','a.company_name','a.cin_number','a.user_id','a.created_at','a.price')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>$type,'a.business_id'=>$business_id]);
                    if($condition)
                    {
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime('-7 days')))
                            ->whereDate('a.created_at','<=',date('Y-m-d'));
                    }
                    $data = $data->orderBy('a.id','desc')
                    ->get();
        }

        return $data;
    }

    public static function get_verification_service($guest_v_id,$service_id)
    {
        $data=NULL;
        $data=DB::table('guest_verification_services')->where(['gv_id'=>$guest_v_id,'service_id'=>$service_id])->first();
        if($data!=NULL)
        {
            $data=$data;
        }

        return $data;
    }

    public static function get_promo_code($promo_code_id)
    {
        $data=DB::table('promocodes')->where(['id'=>$promo_code_id])->first();
        return $data;
    }

    public static function get_batch_data($batch_id)
    {
       $batch_status =NULL;
        $batch_status= DB::table('batch_masters')->where(['id'=>$batch_id,'status'=>'2'])->first();
        if ($batch_status!=NULL) {

             $batch_status=$batch_status->status;
        }

        return $batch_status;
    }

    public static function get_help_response_data($help_id,$business_id)
    {
       $help_resopnse='';
       $help_resopnse=DB::table('help_and_support_responses')->where(['help_request_id'=>$help_id,'business_id'=>$business_id])->first();
       if ($help_resopnse) {
           $help_resopnse =$help_resopnse->response_content;
       }
       return $help_resopnse;
    }

    public static function get_job_items($candidate_id,$business_id)
    {
        $data=NULL;
        $data=DB::table('job_items')->where(['candidate_id'=>$candidate_id,'business_id'=>$business_id])->first();

        return $data;

    }

    public static function get_customer_sla($business_id)
    {
        $data=NULL;

        $data= DB::table('customer_sla as sla')
                ->select('sla.*')
                ->where(['sla.business_id'=>$business_id])
                ->get();

        return $data;
    }

    public static  function get_additional_address_data($candidate_id,$report_item_id)
    {
        $additional_data =null;
        $additional_data = DB::table('additional_address_verifications')->where(['candidate_id'=>$candidate_id,'report_item_id'=>$report_item_id])->first();
        
        return $additional_data;
       
    }

    //Get task condition  for candidate module.
    public static function get_is_cam($business_id)
    {
        $cam=NULL;
        // dd($business_id);
        $user_business_id= Auth::user()->business_id;
        $user_id = Auth::user()->id;

       


        $cam= DB::table('key_account_managers')
                ->select('id')
                ->where(['business_id'=>$business_id,'user_id'=>$user_id])
                ->first();
            // dd($user_id);
        //   if (empty($cam)) {

            // if ($jaf_status=='pending' || $jaf_status=='draft') {

            //     $data=DB::table('tasks')->where(['business_id'=>$business_id,'candidate_id'=>$candidate_id,'description'=>'JAF Filling'])->whereNull('assigned_to')->first();
           
            // }else {

            //     $data=DB::table('tasks')->where(['business_id'=>$business_id,'candidate_id'=>$candidate_id,'description'=>'Task for Verification'])->whereNull('assigned_to')->first();

            // }
            return $cam;

    }      

        
    public static function get_instant_check_price($business_id,$service_id)
    {
        $data=NULL;

        $data=DB::table('guest_instant_check_prices')->where(['business_id'=>$business_id,'service_id'=>$service_id])->first();

        return $data;
    }

    public static function get_instant_cart($business_id,$service_id,$guest_master_id)
    {
        $data = NULL;

        $data=DB::table('guest_instant_carts')
                ->where(['business_id'=>$business_id,'service_id'=>$service_id,'giv_m_id'=>$guest_master_id])
                ->first();
        return $data;
    }
    public static function get_instant_cart_service($giv_m_id,$giv_c_id,$service_id)
    {
        $data=DB::table('guest_instant_cart_services')
                ->where(['giv_m_id'=>$giv_m_id,'giv_c_id'=>$giv_c_id,'service_id'=>$service_id])
                ->get();
        return $data;
    }
    public static function get_instant_cart_services_slot($giv_m_id,$giv_c_id)
    {
        $data=DB::table('guest_instant_cart_services')
                ->where(['giv_c_id'=>$giv_c_id])
                ->get();

        return $data;
    }

    public static function get_guest_common_form_inputs()
    {
        $data=DB::table('guest_service_form_inputs')->where('service_id',0)->get();

        return $data;
    }

    public static function get_guest_service_form_inputs($service_id)
    {
        $data=DB::table('guest_service_form_inputs')->where('service_id',$service_id)->get();

        return $data;
    }
    //get counrty name from country_id
    public static function get_country_name($country_id)
    {
        $data =null;
        $data=DB::table('countries')->select('name')->where('id',$country_id)->first();
       
        return $data;
    }
    public static function get_service_inputs(int $service_id)
    {
        $res_data = "";
        $data = DB::table('service_form_inputs as sfi')
                    ->select('sfi.*')            
                    ->where(['sfi.service_id'=>$service_id,'is_deleted'=>'0'])
                    ->get();
        return $data;
    }

    public static function user_business_details(int $business_id)
    {
        $data=NULL;
        $data=DB::table('users as u')
        ->select('u.*','ub.company_name','ub.gst_number','ub.tin_number','ub.address_line1 as business_address','ub.city_name','ub.state_name','ub.country_name','ub.zipcode','ub.phone as business_phone','ub.website','ub.email as business_email')
        ->join('user_businesses as ub','ub.business_id','=','u.id')
        ->where('u.id',$business_id)
        ->first();

        return $data;
    }

    //Get State name for zone 
    public static function get_state_name($name,$business_id)
    {
        $data =null;
        $res_data='';
        $state=[];
        $data=DB::table('zone_masters')->select('state_id')->where(['name'=>$name,'business_id'=>$business_id])->get();
        if (count($data)>0) {
    
            foreach ($data as $item) {
            $state[]= $item->state_id;
            }

            $states = DB::table('states')->whereIn('id',$state)->get();
            if (count($states)>0) {
               foreach ($states as $value) {
                $res_data .=' <span class="badge badge-secondary" style="padding: 5px;
                font-size: 11px;">' .$value->name .'</span>';
               }
            
            }
        }
        

        return $res_data;
    }

    public static function get_cities($name,$business_id)
    {
        $zone_city   = DB::table('zone_masters')->select('city_id')->where(['name'=>$name,'business_id'=>$business_id])->get();
       foreach ($zone_city as $value) {
         $city_id[]= $value->city_id;
      }
      return $city_id;
    }

    public static function get_vendor_verification_status($task_id)
    {
        $res_data = 0;
        $data = DB::table('vendor_verification_statuses')           
                ->where(['vendor_task_id'=>$task_id])
                ->count();
        if($data >0){
            $res_data = $data;
        }   

        return $res_data; 
        
    }

    public static function convert_to_words($num)
    {
        // Get number of digits
        // in given number
        $len = strlen($num);
    
        // Base cases
        if ($len == 0)
        {
            return "empty string";
        }
        if ($len > 4)
        {
            return "Length more than 4 " .
            "is not supported";;
        }
    
        /* The first string is not used,
        it is to make array indexing simple */
        $single_digits = array("Zero", "One", "Two",
                            "Three", "Four", "Five",
                            "Six", "Seven", "Eight",
                                            "Nine");
    
        /* The first string is not used,
        it is to make array indexing simple */
        $two_digits = array("", "Ten", "Eleven", "Twelve",
                            "Thirteen", "Fourteen", "Fifteen",
                            "Sixteen", "Seventeen", "Eighteen",
                                                "Nineteen");
    
        /* The first two string are not used,
        they are to make array indexing simple*/
        $tens_multiple = array("", "", "Twenty", "Thirty",
                            "Forty", "Fifty", "Sixty",
                            "Seventy", "Eighty", "Ninety");
    
        $tens_power = array("Hundred", "Thousand");
    
        /* Used for debugging purpose only */
        /* echo $num.": ";*/
    
        /* For single digit number */
        if ($len == 1)
        {
            return $single_digits[$num[0] - '0'];
        }
    
        /* Iterate while num
            is not '\0' */
        $x = 0;
        $single='';
        while ($x < strlen($num))
        {
            /* Code path for first 2 digits */
            if ($len >= 3)
            {
                if ($num[$x]-'0' != 0)
                {
                    $single.=$single_digits[$num[$x] - '0'] . " " . $tens_power[$len - 3] . "";
                    // here len can be 3 or 4
                }
                --$len;
            }
    
            /* Code path for last 2 digits */
            else
            {
                /* Need to explicitly handle
                10-19. Sum of the two digits
                is used as index of "two_digits"
                array of strings */
                if ($num[$x] - '0' == 1)
                {
                    if($num=="10")
                    {
                        return $single.$two_digits[1];
                    }
                    else if($num=="11")
                    {
                        return $single.$two_digits[2];
                    }
                    else if($num=="12")
                    {
                        return $single.$two_digits[3];
                    }
                    else if($num=="13")
                    {
                        return $single.$two_digits[4];
                    }
                    else if($num=="14")
                    {
                        return $single.$two_digits[5];
                    }
                    else if($num=="15")
                    {
                        return $single.$two_digits[6];
                    }
                    else if($num=="16")
                    {
                        return $single.$two_digits[7];
                    }
                    else if($num=="17")
                    {
                        return $single.$two_digits[8];
                    }
                    else if($num=="18")
                    {
                        return $single.$two_digits[9];
                    }
                    else if($num=="19")
                    {
                        return $single.$two_digits[10];
                    }
                    else
                    {
                        return $single;
                    }
                    // $sum = $num[$x] - '0' +
                    //     $num[$x] - '0';
                    // return $single.$two_digits[$sum];
                }
    
                /* Need to explicitely handle 20 */
                else if ($num[$x] - '0' == 2 &&
                        $num[$x + 1] - '0' == 0)
                {
                    return $single."Twenty";
                }
    
                /* Rest of the two digit
                numbers i.e., 21 to 99 */
                else
                {
                    $i = $num[$x] - '0';
                    // dd($i);
                    if($i > 0)
                    $single.=$tens_multiple[$i];
                    else
                    $single.="";
                    ++$x;
                    if ($num[$x] - '0' != 0)
                        $single.=" ".$single_digits[$num[$x] -
                                        '0'];
                  
                }
            }
            ++$x;
        }
        return $single;

    }

    public static function numberTowords($amount)
    {
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words=array(0 => '');
        $word='';
        for($i=1;$i<100;$i++)
        {
            $word='';
            $word=Helper::convert_to_words(strval($i));

            $change_words[$i]=$word;
        }
        // dd($count_length);
        // dd($amount_after_decimal);
        // dd($change_words);
        // dd($change_words[strval($amount_after_decimal)]);
        // dd($num);
        // dd($change_words);
        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore','Arab');
        while( $x < $count_length ) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' ' : null;
                $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
                '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
                '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
                }else $string[] = null;
            }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[strval($amount_after_decimal)]) . ' Paise' : '';
        return $implode_to_Rupees;
        // return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise . ' Only';
        // $number = $amount;
        // $no = floor($number);
        //     $point = round($number - $no, 2) * 100;
        //     $hundred = null;
        //     $digits_1 = strlen($no);
        //     // dd($digits_1);
        //     $i = 0;
        //     $str = array();
        //     // $words = array('0' => '', '1' => 'one', '2' => 'two',
        //     //     '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        //     //     '7' => 'seven', '8' => 'eight', '9' => 'nine',
        //     //     '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        //     //     '13' => 'thirteen', '14' => 'fourteen',
        //     //     '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        //     //     '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        //     //     '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        //     //     '60' => 'sixty', '70' => 'seventy',
        //     //     '80' => 'eighty', '90' => 'ninety');

        //     $words = array(0 => '');
        //     $word='';
        //     for($i=1;$i<100;$i++)
        //     {
        //         $word='';
        //         $word=Helper::convert_to_words(strval($i));

        //         $words[$i]=$word;
        //     }
        //     // dd($words);
        //     $digits = array('', 'Hundred','Thousand','Lakh', 'Crore','Arab');
        //     while ($i < $digits_1) {
        //         $divider = ($i == 2) ? 10 : 100;
        //         $number = floor($no % $divider);
        //         $no = floor($no / $divider);
        //         $i += ($divider == 10) ? 1 : 2;
        //         if ($number) {
        //             $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        //             $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        //             $str [] = ($number < 21) ? $words[$number] .
        //                 " " . $digits[$counter] . $plural . " " . $hundred
        //                 :
        //                 $words[floor($number / 10) * 10]
        //                 . " " . $words[$number % 10] . " "
        //                 . $digits[$counter] . $plural . " " . $hundred;
        //         } else $str[] = null;
        //     }
        //     $str = array_reverse($str);
        //     $result = implode('', $str);
        //     $points = ($point) ?
        //         "." . $words[$point / 10] . " " . 
        //             $words[$point = $point % 10] : '';
        //     return $result;
    }

    public static function billingApproval($billing_id)
    {
        $data=NULL;

        $data=DB::table('billing_approvals')->where(['billing_id'=>$billing_id])->latest()->first();
        
        return $data;
    }

    public static function user_details($id)
    {
        $data = NULL;

        $data = DB::table('users')->where(['id'=>$id])->first();

        return $data;
    } 

    public static function candidate_user_details($id)
    {
        $data = NULL;

        $data = CandidateReinitiate::where(['id'=>$id])->first();

        return $data;
    } 

    public static function get_guest_instant_cart_service_name_slot($guest_instant_cart_id)
    {

        $guest_instant_cart_id_set= explode(',',$guest_instant_cart_id);
        $res_data = '';
        $service_name = '';
        $data = DB::table('guest_instant_carts as gc')
                    ->select('s.name','gc.id','s.id as service_id')            
                    ->join('services as s','s.id','=','gc.service_id')
                    ->whereIn('gc.id',$guest_instant_cart_id_set)
                    ->get();
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    if(stripos($value->name,'Driving')!==false)
                    {
                        $service_name = 'Driving License';
                    }
                    else
                    {
                        $service_name = $value->name;
                    }
                    $res_data .='<a href="'.url('/verify/').'/instantverification/orders/details/'.Crypt::encryptString($value->id).'" title="Order Details" class="px-1"><span class="badge badge-secondary order_data" style="padding: 5px;
                    font-size: 10px; padding-left:5px;">' .$service_name.'</span></a>';
                }
                
            }
        return $res_data;
    }

    public static function get_guest_instant_cart_service_name_slot_check_details($guest_instant_cart_id)
    {

        $guest_instant_cart_id_set= explode(',',$guest_instant_cart_id);
        $res_data = '';
        $service_name = '';
        $data = DB::table('guest_instant_carts as gc')
                    ->select('s.name','gc.id','s.id as service_id')            
                    ->join('services as s','s.id','=','gc.service_id')
                    ->whereIn('gc.id',$guest_instant_cart_id_set)
                    ->get();
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    if(stripos($value->name,'Driving')!==false)
                    {
                        $service_name = 'Driving License';
                    }
                    else
                    {
                        $service_name = $value->name;
                    }
                    $res_data .='<a href="'.url('/verify/').'/checkdetails/details/'.Crypt::encryptString($value->id).'" title="Order Details" class="px-1"><span class="badge badge-secondary order_data" style="padding: 5px;
                    font-size: 10px; padding-left:5px;">' .$service_name.'</span></a>';
                }
                
            }
        return $res_data;
    }

    public static function get_guest_instant_cart_service_name_slot_check_details_reports($guest_instant_cart_id)
    {

        $guest_instant_cart_id_set= explode(',',$guest_instant_cart_id);
        $res_data = '';
        $service_name = '';
        $data = DB::table('guest_instant_carts as gc')
                    ->select('s.name','gc.id','s.id as service_id')            
                    ->join('services as s','s.id','=','gc.service_id')
                    ->whereIn('gc.id',$guest_instant_cart_id_set)
                    ->get();
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    if(stripos($value->name,'Driving')!==false)
                    {
                        $service_name = 'Driving License';
                    }
                    else
                    {
                        $service_name = $value->name;
                    }
                    $res_data .='<a href="'.url('/verify/').'/instantverification/reports/reportsdetails/'.Crypt::encryptString($value->id).'" title="Order Details" class="px-1"><span class="badge badge-secondary order_data" style="padding: 5px;
                    font-size: 10px; padding-left:5px;">' .$service_name.'</span></a>';
                }
                
            }
        return $res_data;
    }

    public static function get_instant_order_report_status($guest_instant_cart_id)
    {
        $guest_instant_cart_id_set= explode(',',$guest_instant_cart_id);

        $res_data = '';

        $success_count = DB::table('guest_instant_cart_services')
                            ->whereIn('giv_c_id',$guest_instant_cart_id_set)
                            ->where('status','success')
                            ->count();

        $failed_count = DB::table('guest_instant_cart_services')
                            ->whereIn('giv_c_id',$guest_instant_cart_id_set)
                            ->where('status','failed')
                            ->count();
        $res_data.='<span class="text-danger"> Insuff -</span> '.$failed_count.', 
                    <span class="text-success">Clear -</span> '.$success_count.'';
        
        return $res_data;

    }
    
    public static function reportPdfToImage($report_item_id,$file_name,$user_id)
    {
        $img_url='';
        $report_data=DB::table('report_items as ri')
                    ->select('ri.*','s.name as service_name','s.verification_type','ri.service_item_number as item_no','r.business_id')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->join('reports as r','r.id','=','ri.report_id')
                    ->where(['ri.id'=>$report_item_id])
                    ->first();
        
        if($report_data!=NULL)
        {
            $path=public_path('/').'/uploads/report-files/';
            
            if(File::exists($path.$file_name))
            {
                $report_path=public_path('/').'/uploads/report-data/'.$user_id.'/';
                
                $imagick = new Imagick();
                $imagick->setResolution(300, 300);

                $imagick->readImage($path.$file_name);

                $imagick->setImageFormat("png");
                // // $imagick->resizeImage(200,200,1,0);
                // header("Content-Type: image/jpeg");
                // $thumbnail = $imagick->getImageBlob();
                $pages = $imagick->getNumberImages();
                // dd($pages);
                // return "<img src='data:image/jpg;base64,".base64_encode($thumbnail)."' width='100px' height='100px'/>";

                $imagick->writeImages($report_path.$report_data->id.$report_data->service_name.'-'.$report_data->item_no.'.png', false);
                if($pages)
                {
                    if($pages==1)
                    {
                        $img_url.="<div style='border:solid 1px #333; text-align:center; margin-top:15px;'>
                                        <img src='".url('/').'/uploads/report-data/'.$user_id.'/'.$report_data->id.$report_data->service_name.'-'.$report_data->item_no.'.png'."' style='width:100%; height: 100%; margin:10px 0;'>
                                    </div>";
                    }
                    else
                    {
                        for($i=0;$i<$pages;$i++)
                        {

                            $img_url.="<div style='border:solid 1px #333; text-align:center; margin-top:15px;'>
                                            <img src='".url('/').'/uploads/report-data/'.$user_id.'/'.$report_data->id.$report_data->service_name.'-'.$report_data->item_no.'-'.$i.'.png'."' style='width:100%; height: 100%; margin:10px 0;'>
                                        </div>";
                        }
                    }
                }
            }

        }

        return $img_url;
    }

    public static function referenceServiceFormInputs($service_id,$type)
    {
        $ref_service_inputs = DB::table('service_form_inputs')
                                ->where(['service_id'=>$service_id,'reference_type'=>$type,'status'=>1])
                                ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                                ->orderBy('reference_type','desc')
                                ->get();

        return $ref_service_inputs;
    }

    public static function get_superadmin_guest_instant_cart_service_name_slot($guest_instant_cart_id)
    {

        $guest_instant_cart_id_set= explode(',',$guest_instant_cart_id);
        $res_data = '';
        $data = DB::table('guest_instant_carts as gc')
                    ->select('s.name','gc.id','s.id as service_id')            
                    ->join('services as s','s.id','=','gc.service_id')
                    ->whereIn('gc.id',$guest_instant_cart_id_set)
                    ->get();
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    $res_data .='<a href="'.url('/app/').'/guest/order/details/'.Crypt::encryptString($value->id).'" title="Order Details"><span class="badge badge-secondary order_data" style="padding: 5px;
                    font-size: 11px;">' .$value->name .'</span></a>';
                }
                
            }
        return $res_data;
    }

    public static function get_guest_service_name_slot($service_id)
    {
        $service_id_set=explode(',',$service_id);
        $res_data = '';
        $service_name = '';
        $data = DB::table('services')
                    ->select('name')            
                    ->whereIn('id',$service_id_set)
                    ->get();
            if(count($data)>0){
                foreach ($data as $key => $value) {
                    if(stripos($value->name,'Driving')!==false)
                    {
                        $service_name = 'Driving License';
                    }
                    else
                    {
                        $service_name = $value->name;
                    }
                    $res_data .=' <span class="badge badge-secondary" style="padding: 5px;
                    font-size: 10px;">' .$service_name .'</span>';
                }
                
            }
        return $res_data;
    }

    public static function get_guest_help_response_data($help_id,$business_id)
    {
       $help_response=NULL;
       $help_response=DB::table('guest_help_and_support_responses')->where(['help_request_id'=>$help_id,'business_id'=>$business_id])->first();
       if ($help_response) {
           $help_response =$help_response->response_content;
       }
       return $help_response;
    }

    public static function get_guest_instant_master_data($guest_master_id)
    {
        $data = NULL;

        $data = DB::table('guest_instant_masters')->where(['id'=>$guest_master_id])->first();

        return $data;
    }
    public static function get_vendor_completed_task($task_id,$service_id,$no_of_verification)
    {
        $vendor_task ='';
        $vendor_task = DB::table('vendor_tasks')->select('completed_by')->where(['task_id'=>$task_id,'service_id'=>$service_id,'no_of_verification'=>$no_of_verification,'status'=>'2'])->first();

        return $vendor_task;
    }

    public static function get_vendor_task($task_id,$service_id,$no_of_verification)
    {
        $vendor_task ='';
        $vendor_task = DB::table('vendor_tasks')->select('completed_by')->where(['task_id'=>$task_id,'service_id'=>$service_id,'no_of_verification'=>$no_of_verification])->whereIn('status',['1','2'])->first();

        return $vendor_task;
    }
    //Get vendor task assignment data for show or hide reassignmnet button
    public static function get_vendor_task_reassignment($task_id)
    {
        $vendor_task_reassignment ='';
        $vendor_task_reassignment = DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNotNull('assigned_to')->count();
        // dd($vendor_task_reassignment);
        return $vendor_task_reassignment;
    }
     //Get vendor task assignment data for show or hide assignmnet button
     public static function get_vendor_task_assignment($task_id)
     {
         $vendor_task_assignment ='';
         $vendor_task_assignment = DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNull('assigned_to')->count();
        //  dd($vendor_task_assignment);
         return $vendor_task_assignment;
     }

      //Get vendor task assignment data for show or hide Assigned user name
      public static function get_assigned_task($task_id)
      {
          $vendor_task_assignment ='';
          $vendor_task_assignment = DB::table('vendor_task_assignments')->select('assigned_to')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNotNull('assigned_to')->whereNull('reassigned_to')->first();
        //   dd($vendor_task_assignment);
          return $vendor_task_assignment;
      }
        //Get vendor task assignment data for show or hide Re-Assigned user name
        public static function get_reassigned_task($task_id)
        {
            $vendor_task_assignment ='';
            $vendor_task_assignment = DB::table('vendor_task_assignments')->select('reassigned_to')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNotNull('assigned_to')->whereNotNull('reassigned_to')->first();
            //dd($vendor_task_assignment);
            return $vendor_task_assignment;
        }
 //Get digital address verifications data 
    public static function get_digital_data($jaf_id)
    {
        $digital_data ='';
        $digital_data = DB::table('digital_address_verifications')->where(['jaf_id'=>$jaf_id,'status'=>'1'])->first();
        //dd($vendor_task_assignment);
        return $digital_data;
    }

    public static function get_billing_action($billing_id)
    {
        $data = DB::table('billing_approval_actions')->where(['billing_id'=>$billing_id])->get();

        return $data;
    }

    public static function get_ecourt_master_items($master_id)
    {
        $data = DB::table('e_court_check_master_items')->where(['e_court_master_id'=>$master_id])->get();

        return $data;
    }
    public static function company_type($vendor_id)
    {
        $data = DB::table('vendor_businesses')->select('vendor_type')->where(['vendor_id'=>$vendor_id])->first();

        return $data;
    }

        public static function get_billing_details($billing_id)
        {
            $data = [];

            $data=DB::table('billing_items as bi')
            ->select('bi.*','s.verification_type')
            ->join('services as s','s.id','=','bi.service_id')
            ->where('bi.billing_id',$billing_id)
            ->orderBy('bi.service_id','asc')
            ->get();

            return $data;
        }
        
        public static function round_up ( $value, $precision ) { 
            $pow = pow ( 10, $precision ); 
            return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
        }

        public static function billing_discount($billing_id)
        {
            $data = NULL;

            $data = DB::table('billing_discounts')->where(['billing_id'=>$billing_id])->first();

            return $data;
        }

        public static function get_states_list($country_id)
        {
            $data = DB::table('states')->where('country_id',$country_id)->get();

            return $data;
        }

        public function get_city_list($state_id)
        {
            $data = DB::table('cities')->where('state_id',$state_id)->get();

            return $data;
        }

        //Quickbook sent customer list 

        public static function quickbook_customer($id)
        {
            $customer_data = null;
            $data = DB::table('quicksbook_customers')->where('business_id',$id)->first();
            if ($data) {
               
                $customer_data =$data;
            }

            return $customer_data;
        }
        /*
    * @return mix
    */
    
    public static function vendor_company_logo(int $business_id)
    {
        $res_data = "";
        $logo_data = DB::table('users')
                    ->select('company_logo','company_logo_file_platform')                
                    ->where(['business_id'=>$business_id])
                    ->first();

                if($logo_data !=null){
                    if(stripos($logo_data->company_logo_file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/company-logo/';

                        $file_name = $logo_data->company_logo;
    
                        $s3_config = S3ConfigTrait::s3Config();
    
                        $disk = Storage::disk('s3');
    
                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                'Key'                        => $filePath.$file_name,
                                'ResponseContentDisposition' => 'attachment;'//for download
                        ]);
    
                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');
    
                        $res_data = (string)$req->getUri();
                    }
                    else
                    {
                        $res_data = url('/').'/uploads/company-logo/'.$logo_data->company_logo;  
                    }
                    
                    $res_data = "<img style='height:45px; object-fit:contain; width:150px;' src='".$res_data."' alt=''>";  
                }
                if( $logo_data->company_logo ==null){

                $company_data = DB::table('vendors')
                    ->select('company_name','first_name','last_name')                
                    ->where(['user_id'=>$business_id])
                    ->first();

                    if($company_data !=null){
                        if($company_data->company_name!=null)
                            $res_data = ucfirst($company_data->company_name);
                        else
                            $res_data = $company_data->first_name.' '.$company_data->last_name;
                    }
                }

        return $res_data;
        
    }

     //
     public static function vendor_company_name(int $business_id)
     {
         $res_data = "";
         $company_data = DB::table('vendors')
             ->select('company_name','first_name','last_name')                
             ->where(['user_id'=>$business_id])
             ->first();
 
             if($company_data !=null){
                if($company_data->company_name!=null)
                    $res_data = ucfirst($company_data->company_name);
                else
                    $res_data = $company_data->first_name.' '.$company_data->last_name;
             }
 
         return $res_data;
         
     }

     public static function get_task_sla($user_id,$job_sla_item_id)
     {
        $res_data = "";
        $user= DB::table('users')->where('id',$user_id)->first();
        //    dd($user);
        if($user){
           
            // dd($sla_id);
            if($user->user_type=='vendor_user'|| $user->user_type=='vendor')
            {

                // if ($sla_id != null) {
                    # code...
                        
                    $data = DB::table('vendor_slas as sla')
                            ->select('sla.title')            
                            ->where(['sla.id'=>$job_sla_item_id])
                            ->first();

                    if($data !=null){
                        $res_data = $data->title;
                    }
                // }   
            }
            else{
                $sla_id =  DB::table('job_sla_items as jsi')
                ->select('jsi.sla_id')            
                ->where(['id'=>$job_sla_item_id])->first();
                if ($sla_id != null) {
                    # code...
                      
                    $data = DB::table('customer_sla as sla')
                            ->select('sla.title')            
                            ->where(['sla.id'=>$sla_id->sla_id])
                            ->first();

                    if($data !=null){
                        $res_data = $data->title;
                    }
                }   
            }
            // $sla_id =  DB::table('job_sla_items as jsi')
            // ->select('jsi.sla_id')            
            // ->where(['id'=>$job_sla_item_id])->first();
               
        }
        return $res_data;
     }

    public static function array_push_assoc($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }

    public static function get_duplicates_array( $array ) {
        return array_unique( array_diff_assoc( $array, array_unique( $array ) ) );
    }

    public static function get_report_item_services($candidate_id,$service_id)
    {
        $data = DB::table('report_items as ri')
                    ->select('ri.*','s.verification_type')
                    ->join('services as s','ri.service_id','=','s.id')
                    ->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id])
                    ->get();
        
        return $data;
    }

    public static function get_total_hours($start_date,$end_date)
    {
        
        $timestamp1 = strtotime($start_date);
        $timestamp2 = strtotime($end_date);

        return number_format(abs($timestamp2 - $timestamp1)/(60*60),2);
    }

    public static function createFileObject($url){

        $path_parts = pathinfo($url);

        $newPath = $path_parts['dirname'] . '/tmp-files/';
        if(!is_dir ($newPath)){
            mkdir($newPath, 0777);
        }

        $newUrl = $newPath . $path_parts['basename'];
        copy($url, $newUrl);
        // $imgInfo = getimagesize($newUrl);

        $file = new UploadedFile(
            $newUrl,
            $path_parts['basename'],
            NULL,
            filesize($url),
            true,
            TRUE
        );

        return $file;
    }

    public static function assignTask($user_id,$business_id)
    {
        $ver_assign_task =DB::table('tasks as t')
        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
        ->whereIn('ta.status',['1','2'])
        ->whereNotNull('t.assigned_to')
        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', t.assigned_to='.$user_id.')')->count();

        return $ver_assign_task;
    }
    public static function pendingTask($user_id,$business_id)
    {
        $ver_assign_task =DB::table('tasks as t')
        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'1'])
        ->whereNotNull('t.assigned_to')
        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', t.assigned_to='.$user_id.')')->count();

        return $ver_assign_task;
    }
    
    public static function completedTask($user_id,$business_id)
    {
        $ver_assign_task =DB::table('tasks as t')
        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2'])
        ->whereNotNull('t.assigned_to')
        ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user_id.', t.assigned_to='.$user_id.')')->count();

        return $ver_assign_task;
    }

    public static function addressVerificationData($jaf_id)
    {
        $data = DB::table('address_verifications')->where(['jaf_id'=>$jaf_id])->latest()->first();

        return $data;
    }

    public static function addressVerificationFile($jaf_id,$file_type)
    {
        $data = DB::table('address_verification_file_uploads')->where(['jaf_id'=>$jaf_id,'file_type'=>$file_type])->get();

        return $data;
    }

    public static function drugTestName($service_id)
    {
        $data = DB::table('drug_test_names')->where('service_id',$service_id)->get();
        return $data;
    }

    public static function serviceVitalsName($service_id)
    {
        $data = DB::table('service_vitals')->where('service_id',$service_id)
                ->select('id','vital_name','vital_full_name')
                ->where('parent_id','<>',0)
                ->where('status',1)
                ->whereNotIn('vital_name',['V4CRI','V4CRT','Vital4Trace'])
                ->get();
        return $data;
    }

    /*
    * @return mix
    */
    public static function get_report_item_approval_status($report_item_id)
    {
        $data = NULL;
        $report_item = DB::table('report_items')
                    ->select('approval_status_id')            
                    ->where(['id'=>$report_item_id])
                    ->first();
            if($report_item !=null){
                $data = DB::table('report_status_masters')
                        ->select('name','color_code','color_name')            
                        ->where(['id'=>$report_item->approval_status_id])
                        ->first();
                
            }
        return $data;
    }

    public static function get_jaf_data($jaf_id)
    {
        //
        $data=DB::table('jaf_form_data as jf')
                ->select('jf.*','s.name as service_name','s.verification_type','s.type_name')
                ->join('services as s','jf.service_id','=','s.id')
                ->where(['jf.id'=>$jaf_id])
                ->first();

                
        return $data;
    }

    public static function getNotificationControlData($business_id,$type)
    {
        $data = NotificationControl::where('business_id',$business_id)->where('type',$type)->latest()->first();

        return $data;
    }

    public static function getJafServiceNameSlot($jaf_arr_id)
    {
        $res_data = "";

        $data = DB::table('jaf_form_data as j')
                ->select('j.*','s.name as service_name','s.verification_type')
                ->join('services as s','s.id','=','j.service_id')
                ->whereIn('j.id',$jaf_arr_id)
                ->get();

        $count=count($data);
        $i=0;

        if(count($data)>0)
        {
            foreach($data as $value)
            {
                $service_name = $value->verification_type=='Manual' ? $value->service_name.' - '.$value->check_item_number : $value->service_name;

                if(++$i==$count)
                {
                    $res_data.= $service_name;
                }
                else
                {
                    $res_data.= $service_name.', ';
                }
            }
        }

        return $res_data;
    }

    public static function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];

        $tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        $arr=['tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date];

        return $arr;
        
    }

    public static function calenderDays($start_date,$holidays,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        // $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        foreach($holidays as $holiday)
        {
        
            $holiday_ts = strtotime($holiday->date);

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $tat_new_date = date('Y-m-d', strtotime("{$tat_new_date} + 1 weekdays"));
                }
            }

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($inc_tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $inc_tat_new_date = date('Y-m-d', strtotime("{$inc_tat_new_date} + 1 weekdays"));
                }
            }
        }

        return array('tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date);
    }

    /*
    * @return mix
    */
    public static function get_report_approval_status_name($report_id)
    {
        $res_data = "";
        $data = DB::table('reports')
                    ->select('approval_status_id')            
                    ->where(['id'=>$report_id])
                    ->first();
            if($data !=null){
                $status = DB::table('report_status_masters')
                        ->select('name','color_code')            
                        ->where(['id'=>$data->approval_status_id])
                        ->first();
                        if ($status) {
                            $res_data =  $status->name;
                        }
                
            }
        return $res_data;
    }

    /*
    * @return mix
    */
    public static function get_vendor_sla($sla_id)
    {
        $res_data = "";
        $data = DB::table('vendor_slas')
                    ->select('title')            
                    ->where(['id'=>$sla_id])
                    ->first();
            if($data !=null){
               $res_data =  $data->title;
                    
                
            }
        return $res_data;
    }

    /*
    * @return mix
    */
    public static function get_ready_report($candidate_id)
    {
        $res_data = "";
        $data = DB::table('job_items')
                    ->select('is_jaf_ready_report')            
                    ->where(['candidate_id'=>$candidate_id])
                    ->first();
            if($data !=null){
               $res_data =  $data->is_jaf_ready_report;
            }
        return $res_data;
    }

    public static function role_permission_action($user_id,$route_group='',$action_title)
    {
        $res_data=NULL;

        $user_role = RoleMaster::from('role_masters as r')
                ->select('r.*','rp.permission_id')
                ->join('users as u','u.role','=','r.id')
                ->join('role_permissions as rp','rp.role_id','=','r.id')
                ->where('u.id',$user_id)
                ->first();

        if($user_role!=NULL)
        {
            $permission=$user_role->permission_id;

            $p_data=json_decode($permission,true);

            if(count($p_data)>0)
            {
                $res_data = DB::table('action_masters')->whereIn('id',$p_data)->where('route_group',$route_group)->where('action_title',$action_title)->first();
            }
        }

        return $res_data;
        
    }

    public static function referenceServiceFormInputs1($service_id,$type)
    {
        $ref_service_inputs = DB::table('service_form_inputs')
                                ->where(['service_id'=>$service_id,'reference_type'=>$type,'status'=>1])
                                ->orderBy('reference_type','desc')
                                ->get();

        return $ref_service_inputs;
    }


    public static function get_check_item_inputs1(int $service_id)
    {
        $res_data = "";
        $data = DB::table('service_form_inputs as sfi')
                    ->select('sfi.*','s.name','s.type_name')
                    ->join('services as s','s.id','=','sfi.service_id')            
                    ->where(['sfi.service_id'=>$service_id,'sfi.status'=>1])
                    ->whereNull('reference_type')
                    ->get();
        return $data;
    }

    public static function check_item_input_name($service_id,$id,$input_name)
    {
        $data = DB::table('check_control_masters as c')
                    ->select('c.*','si.label_name')
                    ->join('service_form_inputs as si','si.id','=','c.service_input_id')
                    ->where(['c.check_control_coc_id'=>$id,'si.service_id'=>$service_id,'si.label_name'=>$input_name])
                    ->first();
                    
        return $data;
    }

    public static function getUserType($id)
    {
        $data = DB::table('users')->where('id',$id)->first();

        return $data!=NULL ? $data->user_type : NULL;
    }

    public static function getKAMTaskVerification($id,$login_id)
    {
        $data = [];

        $user = DB::table('users')->where('id',$id)->first();

        $login_user = DB::table('users')->where('id',$login_id)->first();

        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$login_id)
                    ->get()->pluck('business_id')->all();

        

        if($user!=NULL)
        {
            $services = DB::table('services as s')
                    ->select('s.name','s.id')
                    ->where(['s.status'=>'1'])
                    ->where('s.business_id',NULL)
                    ->whereNotIn('s.type_name',['e_court','gstin'])
                    ->orwhere('s.business_id',$user->business_id)
                    ->orderBy('s.sort_number','asc')
                    ->get();

            // if(count($kams)<=0)
            // {
            //     // $user_check=DB::table('user_checks')->where('user_id',$user->id)->get()->pluck('checks')->all();

            //     // if(stripos($user->user_type,'user')!==false)
            //     // {
            //     //     $services = DB::table('services as s')->select('s.name','s.id')->whereIn('s.id',$user_check);
            //     // }
            //     // else
            //     // {
            //         $services = DB::table('services as s')
            //         ->select('s.name','s.id')
            //         ->where(['s.status'=>'1'])
            //         ->where('s.business_id',NULL)
            //         ->whereNotIn('s.type_name',['e_court','gstin'])
            //         ->orwhere('s.business_id',$user->business_id);
            //     //}
                    
            //     $services = $services->orderBy('s.sort_number','asc')->get();
            // }
            
            if($login_user->user_type == 'user'){
                $user_check=DB::table('user_checks')->where('user_id',$user->id)->get()->pluck('checks')->all();

                if(stripos($user->user_type,'user')!==false)
                {
                    $services = [];
    
                    if(count($kams)>0 && $user->id == $login_id)
                    {
                        $services = DB::table('services as s')
                                        ->select('s.name','s.id')
                                        ->where(['s.status'=>'1'])
                                        ->where('s.business_id',NULL)
                                        ->whereNotIn('s.type_name',['e_court','gstin'])
                                        ->orwhere('s.business_id',$user->business_id)
                                        ->orderBy('s.sort_number','asc')->get();
    
                    }
                    else
                    {
                        $services = DB::table('services as s')->select('s.name','s.id')->whereIn('s.id',$user_check)->orderBy('s.sort_number','asc')->get();
                    }
                }
            }else if($login_user->user_type == 'customer'){
                $user_check=DB::table('user_checks')->where('user_id',$user->id)->get()->pluck('checks')->all();
                if(stripos($user->user_type,'user')!==false)
                {
                    $services = [];
                    
                    $services = DB::table('services as s')->select('s.name','s.id')->whereIn('s.id',$user_check)->orderBy('s.sort_number','asc')->get();
                }
            }
           

            if(count($services)>0)
            {
                foreach($services as $service)
                {
                    $total_allocated_task = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->join('job_items as j','j.candidate_id','=','u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$user->business_id,'t.description'=>'Task for Verification','ta.service_id'=>$service->id])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to');
                    if(count($kams)>0)
                    {
                        $total_allocated_task->whereIn('t.business_id',$kams);
                    }
                    $total_allocated_task->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user->id.', ta.user_id='.$user->id.')');
        
                    $total_allocated_task=$total_allocated_task->get();

        
                    $total_done_task_in = 0;
        
                    $total_done_task_out = 0;

                    $total_done_task =DB::table('tasks as t')
                                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                                ->join('job_items as j','j.candidate_id','=','u.id')
                                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                                ->where(['u.is_deleted'=>'0','t.parent_id'=>$user->business_id,'ta.status'=>'2','t.description'=>'Task for Verification','ta.service_id'=>$service->id])
                                ->whereNotNull('t.assigned_to')
                                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user->id.', ta.user_id='.$user->id.')');
                                if(count($kams)>0)
                                {
                                    $total_done_task->whereIn('t.business_id',$kams);
                                }

                    $total_done_task=$total_done_task->get();

        
                     // // Total Task Done
                     if(count($total_done_task)>0)
                     {
                         foreach($total_done_task as $item)
                         {
                             $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
        
                             $end_date = $item->completed_at!=NULL ? date('Y-m-d',strtotime($item->completed_at)) : date('Y-m-d',strtotime($item->end_date_at));
        
                             if(stripos($item->tat_type,'case')!==false)
                             {
                                 $date_arr = [];
        
                                 $tat = $item->client_tat - 1;
        
                                 $incentive_tat = $item->client_tat - 1;
        
                                 if(stripos($item->days_type,'working')!==false)
                                 {
                                     $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                 }
                                 else if(stripos($item->days_type,'calender')!==false)
                                 {
                                     $holiday_master=DB::table('customer_holiday_masters')
                                                     ->distinct('date')
                                                     ->select('date')
                                                     ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                     ->orderBy('date','asc')
                                                     ->get();
                                     if(count($holiday_master)>0)
                                     {
                                         $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                     }
                                     else
                                     {
                                         $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                     }
                                 }
        
                                 
                                 if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                 {
                                     $total_done_task_out = $total_done_task_out + 1;
                                 }
                                 else
                                 {
                                     $total_done_task_in = $total_done_task_in + 1;
                                 }

                             }
                             else if(stripos($item->tat_type,'check')!==false)
                             {
                                 $date_arr=[];
        
                                 $tat = 1;
        
                                 $incentive_tat=1;
        
                                 $job_sla_item=DB::table('job_sla_items')
                                                 ->select('tat','incentive_tat')
                                                 ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                 ->first();
        
                                 if($job_sla_item!=NULL)
                                 {
                                     $tat = $job_sla_item->tat;
                                     $incentive_tat = $job_sla_item->incentive_tat;
                                 }
                                 
                                 if(stripos($item->days_type,'working')!==false)
                                 {
                                     $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                 }
                                 else if(stripos($item->days_type,'calender')!==false)
                                 {
                                     $holiday_master=DB::table('customer_holiday_masters')
                                                     ->distinct('date')
                                                     ->select('date')
                                                     ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                     ->orderBy('date','asc')
                                                     ->get();
                                     if(count($holiday_master)>0)
                                     {
                                         $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                     }
                                     else
                                     {
                                         $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                     }
                                 }
        
                                 if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                 {
                                     $total_done_task_out = $total_done_task_out + 1;
                                 }
                                 else
                                 {
                                     $total_done_task_in = $total_done_task_in + 1;
                                 }
        
        
                             }
        
                             
                         }
                     }
        
                     $total_pending_task_in = 0;
        
                     $total_pending_task_out = 0;
        
                     $total_pending_task =DB::table('tasks as t')
                     ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                     ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                     ->join('job_items as j','j.candidate_id','=','u.id')
                     ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                     ->where(['u.is_deleted'=>'0','t.parent_id'=>$user->business_id,'t.description'=>'Task for Verification','ta.status'=>'1','ta.service_id'=>$service->id])
                     ->whereNotNull('t.assigned_to')
                     ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.$user->id.', ta.user_id='.$user->id.')');
                     if(count($kams)>0)
                     {
                        $total_pending_task->whereIn('t.business_id',$kams);
                     }
        
                     $total_pending_task=$total_pending_task->get();
        
                     // Total Task Pending
                     if(count($total_pending_task)>0)
                     {
                         foreach($total_pending_task as $item)
                         {
                             $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
        
                             $end_date = date('Y-m-d');
        
                             if(stripos($item->tat_type,'case')!==false)
                             {
                                 $date_arr = [];
        
                                 $tat = $item->client_tat - 1;
        
                                 $incentive_tat = $item->client_tat - 1;
        
                                 if(stripos($item->days_type,'working')!==false)
                                 {
                                     $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                 }
                                 else if(stripos($item->days_type,'calender')!==false)
                                 {
                                     $holiday_master=DB::table('customer_holiday_masters')
                                                     ->distinct('date')
                                                     ->select('date')
                                                     ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                     ->orderBy('date','asc')
                                                     ->get();
                                     if(count($holiday_master)>0)
                                     {
                                         $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                     }
                                     else
                                     {
                                         $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                     }
                                 }
        
                                 
                                 if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                 {
                                     $total_pending_task_out = $total_pending_task_out + 1;
                                 }
                                 else
                                 {
                                     $total_pending_task_in = $total_pending_task_in + 1;
                                 }
        
                             }
                             else if(stripos($item->tat_type,'check')!==false)
                             {
                                 $date_arr=[];
        
                                 $tat = 1;
        
                                 $incentive_tat=1;
        
                                 $job_sla_item=DB::table('job_sla_items')
                                                 ->select('tat','incentive_tat')
                                                 ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                 ->first();
        
                                 if($job_sla_item!=NULL)
                                 {
                                     $tat = $job_sla_item->tat;
                                     $incentive_tat = $job_sla_item->incentive_tat;
                                 }
                                 
                                 if(stripos($item->days_type,'working')!==false)
                                 {
                                     $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                 }
                                 else if(stripos($item->days_type,'calender')!==false)
                                 {
                                     $holiday_master=DB::table('customer_holiday_masters')
                                                     ->distinct('date')
                                                     ->select('date')
                                                     ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                     ->orderBy('date','asc')
                                                     ->get();
                                     if(count($holiday_master)>0)
                                     {
                                         $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                     }
                                     else
                                     {
                                         $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                     }
                                 }
        
                                 if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                 {
                                     $total_pending_task_out = $total_pending_task_out + 1;
                                 }
                                 else
                                 {
                                     $total_pending_task_in = $total_pending_task_in + 1;
                                 }
        
        
                             }
        
                         }
                     }
        
                     // Total Task Pending Insuff
        
                     $total_insuff_task = 0;
        
                     $total_insuff_task_in = 0;
        
                     $total_insuff_task_out = 0;
        
                     if(count($total_pending_task)>0)
                     {
                         foreach($total_pending_task as $item)
                         {
                             $jaf_form_data = DB::table('jaf_form_data')->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id,'check_item_number'=>$item->number_of_verifications])->where('is_insufficiency','1')->first();
        
                             if($jaf_form_data!=NULL)
                             {
                                 $total_insuff_task = $total_insuff_task + 1;
        
                                 $start_date = $item->start_date!=NULL ? date('Y-m-d',strtotime($item->start_date)) : date('Y-m-d',strtotime($item->assigned_at));
        
                                 $end_date = date('Y-m-d');
        
                                 if(stripos($item->tat_type,'case')!==false)
                                 {
                                     $date_arr = [];
        
                                     $tat = $item->client_tat - 1;
        
                                     $incentive_tat = $item->client_tat - 1;
        
                                     if(stripos($item->days_type,'working')!==false)
                                     {
                                         $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                     }
                                     else if(stripos($item->days_type,'calender')!==false)
                                     {
                                         $holiday_master=DB::table('customer_holiday_masters')
                                                         ->distinct('date')
                                                         ->select('date')
                                                         ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                         ->orderBy('date','asc')
                                                         ->get();
                                         if(count($holiday_master)>0)
                                         {
                                             $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                                         }
                                         else
                                         {
                                             $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                                         }
                                     }
        
                                     
                                     if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                     {
                                         $total_insuff_task_out = $total_insuff_task_out + 1;
                                     }
                                     else
                                     {
                                         $total_insuff_task_in = $total_insuff_task_in + 1;
                                     }
        
                                 }
                                 else if(stripos($item->tat_type,'check')!==false)
                                 {
                                     $date_arr=[];
        
                                     $tat = 1;
        
                                     $incentive_tat=1;
        
                                     $job_sla_item=DB::table('job_sla_items')
                                                     ->select('tat','incentive_tat')
                                                     ->where(['candidate_id'=>$item->candidate_id,'service_id'=>$item->service_id])
                                                     ->first();
        
                                     if($job_sla_item!=NULL)
                                     {
                                         $tat = $job_sla_item->tat;
                                         $incentive_tat = $job_sla_item->incentive_tat;
                                     }
                                     
                                     if(stripos($item->days_type,'working')!==false)
                                     {
                                         $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                     }
                                     else if(stripos($item->days_type,'calender')!==false)
                                     {
                                         $holiday_master=DB::table('customer_holiday_masters')
                                                         ->distinct('date')
                                                         ->select('date')
                                                         ->where(['business_id'=>$item->user_parent_id,'status'=>1])
                                                         ->orderBy('date','asc')
                                                         ->get();
                                         if(count($holiday_master)>0)
                                         {
                                             $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                                         }
                                         else
                                         {
                                             $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                                         }
                                     }
        
                                     if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                                     {
                                         $total_insuff_task_out = $total_insuff_task_out + 1;
                                     }
                                     else
                                     {
                                         $total_insuff_task_in = $total_insuff_task_in + 1;
                                     }
        
        
                                 }
        
                             }
                         }
                     }
        
                     $data[]=[
                                'service'=>$service->name,
                                'service_id' => $service->id,
                                'allocated'=>count($total_allocated_task),
                                'completed'=>count($total_done_task),
                                'completed_in'=>$total_done_task_in,
                                'completed_out'=>$total_done_task_out,
                                'pending'   => count($total_pending_task),
                                'pending_in' => $total_pending_task_in,
                                'pending_out'   => $total_pending_task_out,
                                'insuff'    => $total_insuff_task,
                                'insuff_in' => $total_insuff_task_in,
                                'insuff_out'  => $total_insuff_task_out
                            ];
    
                }
            }
            
        }

        return $data;


    }

    public static function cogentCriminalSLA($business_id,$candidate_id)
    {
        $result = false;

        $data=DB::table('candidate_reinitiates as u')
                    ->distinct('js.service_id')
                    ->select('u.*','js.service_id','s.type_name','js.number_of_verifications')
                    ->join('job_items as j','j.business_id','=','u.id')
                    ->join('job_sla_items as js','js.job_item_id','=','j.id')
                    ->join('services as s','s.id','=','js.service_id')
                    ->where(['u.user_type'=>'client'])
                    ->where('j.candidate_id',$candidate_id)
                    ->where('u.id',$business_id)
                    ->groupBy('js.service_id')
                    ->get();

        if($business_id==1831 && (count($data)==1 && $data->contains('type_name','criminal') && $data->contains('number_of_verifications',1)))
        {
            $result = true;
        }

        return $result;
    }

    public static function checkJafInsuff($candidate_id,$check_id,$check_item_no)
    {
        $jaf_form = DB::table('jaf_form_data')
                        ->select('id')
                        ->where(['candidate_id'=>$candidate_id,'service_id'=>$check_id,'check_item_number'=>$check_item_no])
                        ->where('is_insufficiency',1)
                        ->first();

        return $jaf_form;
    }

    public static function checkTaskTat($task_id)
    {
        // 0 for no result, 1 for in TAT, 2 for Out TAT

        $in_out = 0;

        $task = DB::table('tasks as t')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.reassign_by','ta.created_at as created','ta.updated_at as end_date_at','j.tat_type','j.tat','j.client_tat','j.days_type','j.price_type','u.parent_id as user_parent_id')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->where('t.id',$task_id)
                ->first();

        if($task!=NULL)
        {
            if($task->description=='JAF Filling')
            {
                if($task->tastatus=='1')
                {
                    $start_date = $task->start_date!=NULL ? date('Y-m-d',strtotime($task->start_date)) : date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = date('Y-m-d');

                    $date_arr = [];

                    $tat = $task->client_tat - 1;

                    $incentive_tat = $task->client_tat - 1;

                    if(stripos($task->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($task->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $in_out = 2;
                    }
                    else
                    {
                        $in_out = 1;
                    }
                    
                }
                else if($task->tastatus=='2')
                {
                    $start_date = $task->start_date!=NULL ? date('Y-m-d',strtotime($task->start_date)) : date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = $task->completed_at!=NULL ? date('Y-m-d',strtotime($task->completed_at)) : date('Y-m-d',strtotime($task->end_date_at));

                    $date_arr = [];

                    $tat = $task->client_tat - 1;

                    $incentive_tat = $task->client_tat - 1;

                    if(stripos($task->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($task->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $in_out = 2;
                    }
                    else
                    {
                        $in_out = 1;
                    }
                }
            }
            else if($task->description=='Task for Verification')
            {
                if($task->tastatus=='1')
                {
                    $start_date = $task->start_date!=NULL ? date('Y-m-d',strtotime($task->start_date)) : date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = date('Y-m-d');

                    if(stripos($task->tat_type,'case')!==false)
                    {
                        $date_arr = [];

                        $tat = $task->client_tat - 1;

                        $incentive_tat = $task->client_tat - 1;

                        if(stripos($task->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($task->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $in_out = 2;
                        }
                        else
                        {
                            $in_out = 1;
                        }

                    }
                    else if(stripos($task->tat_type,'check')!==false)
                    {
                        $date_arr=[];

                        $tat = 1;

                        $incentive_tat=1;

                        $job_sla_item=DB::table('job_sla_items')
                                        ->select('tat','incentive_tat')
                                        ->where(['candidate_id'=>$task->candidate_id,'service_id'=>$task->service_id])
                                        ->first();

                        if($job_sla_item!=NULL)
                        {
                            $tat = $job_sla_item->tat;
                            $incentive_tat = $job_sla_item->incentive_tat;
                        }

                        if(stripos($task->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                        }
                        else if(stripos($item->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                            }
                        }

                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $in_out = 2;
                        }
                        else
                        {
                            $in_out = 1;
                        }

                    }
                }
                else if($task->tastatus=='2')
                {
                    $start_date = $task->start_date!=NULL ? date('Y-m-d',strtotime($task->start_date)) : date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = $task->completed_at!=NULL ? date('Y-m-d',strtotime($task->completed_at)) : date('Y-m-d',strtotime($task->end_date_at));

                    if(stripos($task->tat_type,'case')!==false)
                    {
                        $date_arr = [];

                        $tat = $task->client_tat - 1;

                        $incentive_tat = $task->client_tat - 1;

                        if(stripos($task->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                        else if(stripos($task->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                            }
                        }

                        
                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $in_out = 2;
                        }
                        else
                        {
                            $in_out = 1;
                        }

                    }
                    else if(stripos($task->tat_type,'check')!==false)
                    {
                        $date_arr=[];

                        $tat = 1;

                        $incentive_tat=1;

                        $job_sla_item=DB::table('job_sla_items')
                                        ->select('tat','incentive_tat')
                                        ->where(['candidate_id'=>$task->candidate_id,'service_id'=>$task->service_id])
                                        ->first();

                        if($job_sla_item!=NULL)
                        {
                            $tat = $job_sla_item->tat;
                            $incentive_tat = $job_sla_item->incentive_tat;
                        }
                        
                        if(stripos($task->days_type,'working')!==false)
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                        }
                        else if(stripos($task->days_type,'calender')!==false)
                        {
                            $holiday_master=DB::table('customer_holiday_masters')
                                            ->distinct('date')
                                            ->select('date')
                                            ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                            ->orderBy('date','asc')
                                            ->get();
                            if(count($holiday_master)>0)
                            {
                                $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$tat);
                            }
                            else
                            {
                                $date_arr = CommonTrait::workingDays($start_date,$tat,$tat);
                            }
                        }

                        if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                        {
                            $in_out = 2;
                        }
                        else
                        {
                            $in_out = 1;
                        }


                    }
                }
            }
            else if($task->description=='Report generation')
            {
                if($task->tastatus=='1')
                {
                    $start_date = $task->start_date!=NULL ? date('Y-m-d',strtotime($task->start_date)) : date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = date('Y-m-d');

                    $date_arr = [];

                    $tat = $task->client_tat - 1;

                    $incentive_tat = $task->client_tat - 1;

                    if(stripos($task->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($task->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $in_out = 2;
                    }
                    else
                    {
                        $in_out = 1;
                    }
                }
                else if($task->tastatus=='2')
                {
                    $start_date = $task->start_date!=NULL ? date('Y-m-d',strtotime($task->start_date)) : date('Y-m-d',strtotime($task->assigned_at));

                    $end_date = $task->completed_at!=NULL ? date('Y-m-d',strtotime($task->completed_at)) : date('Y-m-d',strtotime($task->end_date_at));

                    $date_arr = [];

                    $tat = $task->client_tat - 1;

                    $incentive_tat = $task->client_tat - 1;

                    if(stripos($task->days_type,'working')!==false)
                    {
                        $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                    }
                    else if(stripos($task->days_type,'calender')!==false)
                    {
                        $holiday_master=DB::table('customer_holiday_masters')
                                        ->distinct('date')
                                        ->select('date')
                                        ->where(['business_id'=>$task->user_parent_id,'status'=>1])
                                        ->orderBy('date','asc')
                                        ->get();
                        if(count($holiday_master)>0)
                        {
                            $date_arr = CommonTrait::calenderDays($start_date,$holiday_master,$tat,$incentive_tat);
                        }
                        else
                        {
                            $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);
                        }
                    }

                    
                    if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                    {
                        $in_out = 2;
                    }
                    else
                    {
                        $in_out = 1;
                    }
                }
            }
        }

        return $in_out;
        
    }

    public static function companyShortName($business_id)
    {
        $res_data = "";

        $company_data = DB::table('user_businesses')
            ->select('company_name','company_short_name')                
            ->where(['business_id'=>$business_id])
            ->first();

            if($company_data !=null){
                $res_data =$company_data->company_short_name!=NULL?$company_data->company_short_name:"";
            }

        return $res_data;

    }

    public static function get_digital_verification_log($jaf_id)
    {
        $data = [];

        $data = DB::table('digital_address_verification_logs')->select('*')->where('jaf_id',$jaf_id)->get();

        return $data;
    }

    public static function addressVerificationFileFirst($jaf_id,$file_type)
    {
        $data = DB::table('address_verification_file_uploads')->where(['jaf_id'=>$jaf_id,'file_type'=>$file_type])->first();

        return $data;
    }

    public static function get_check_report_items($candidate_id,$check_name)
    {
        $data = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$candidate_id,'s.type_name'=>$check_name])
                        ->orderBy('ri.service_item_number','asc')
                        ->get();
                        
        return $data;
    }

    public static function get_check_insuff_jaf_items($candidate_id,$check_name)
    {
        $data = DB::table('jaf_form_data as j')
                   ->join('services as s','s.id','=','j.service_id')
                   ->where(['j.candidate_id'=>$candidate_id,'s.type_name'=>$check_name,'j.is_insufficiency'=>'1'])
                   ->get();
                        
        return $data;
    }
 
    public static function get_service_variable_form_input($id)
    {
        // dd($id);
        // echo $id;
        $res_data=NULL;
        $form_input_master = DB::table('service_form_variable_inputs')->where(['service_form_input_id'=>$id])->first();
       
        if ($form_input_master!=NULL) {
            // dd($form_input_master);
           $res_data=$form_input_master->id;
        }
      
        return $res_data;
    }

    public static function formMasterData($form_id)
    {
        $data = DB::table('form_input_masters')->where('id',$form_id)->first();

        return $data;
    }

    public static function getVariableInput($form_input_id,$parent='')
    {
        $data = DB::table('service_form_variable_inputs')
                ->select('id','service_id','label_name')
                ->where('service_form_input_id',$form_input_id);
                if($parent!='')
                {
                    $data->where('parent_id',$parent);
                }

        $data=$data->get();

        return $data;
    }

    public static function get_service_all_data($id)
    {
        $data = DB::table('services')
                    ->select('name','verification_type','is_multiple_type','is_common','status','sort_number','type_name')            
                    ->where(['id'=>$id])
                    ->first();
        return $data;
    }

    public static function get_vendor_insuff($candidate_id,$service_id,$no_of_verification)
    {
        $data = DB::table('vendor_insufficiencies')
                    ->select('id','status','no_of_verification','service_id')            
                    ->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'no_of_verification'=>$no_of_verification])
                    ->first();
        return $data;
    }

    public static function get_vendor_status_insuff($candidate_id,$service_id,$no_of_verification,$status)
    {
        $data = DB::table('vendor_insufficiencies')
                    ->select('id','status','no_of_verification','service_id')            
                    ->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'no_of_verification'=>$no_of_verification])
                    ->latest()->first();
        return $data;
    }

    public static function get_vendor_attachments($vendor_insuf_id)
    {
        $data = DB::table('vendor_insufficiency_attachments')
                        ->select('id','vendor_insuf_id','attachments')            
                        ->where(['vendor_insuf_id'=>$vendor_insuf_id])
                        ->get();
        return $data;
    }

    public static function get_vendor_verification_data($vendor_task_id)
    {
        $data  = DB::table('vendor_verification_data')
                    ->select('id','file_name','zip_file','file_platform')
                    ->where(['vendor_task_id'=>$vendor_task_id])
                    ->first();          
        return $data;            
    } 


    public static function checkVendorInsuff($candidate_id,$service_id,$no_of_verification)
    {
        $vendor_insuff = DB::table('vendor_insufficiencies')
                        ->select('id','status')
                        ->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'no_of_verification'=>$no_of_verification])
                        ->latest()->first();
        return $vendor_insuff;
    }

    public static function checkVendorTaskTat($task_id)
    {
        // 0 for no result, 1 for in TAT, 2 for Out TAT

        $in_out = 0;

        $task = DB::table('vendor_tasks as vt')
                ->select('t.name','vt.*','s.name as servicename')
                ->join('tasks as t','t.id','=','vt.task_id')
                ->join('services as s','s.id','=','vt.service_id')
                ->where(['vt.id'=>$task_id])
                ->first();

        if($task!=NULL)
        {
            if($task->status=='1')
            {
                $start_date = date('Y-m-d',strtotime($task->assigned_at));

                $end_date = date('Y-m-d');

                $date_arr = [];

                $tat = 1;

                $incentive_tat = 1;

                // $vendor_sla_item = DB::table('vendor_slas')->where(['sla_id'=>$task->vendor_sla_id,'service_id'=>$task->service_id])->first();

                // if($vendor_sla_item!=NULL)
                // {
                //     $tat = $vendor_sla_item->tat - 1;

                //     $incentive_tat = $vendor_sla_item->tat - 1;
                // }

                $tat = $task->tat;

                $incentive_tat = $task->tat;

                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);

                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                {
                    $in_out = 2;
                }
                else
                {
                    $in_out = 1;
                }

            }
            else if($task->status=='2')
            {
                $start_date = date('Y-m-d',strtotime($task->assigned_at));

                $end_date = date('Y-m-d',strtotime($task->completed_at));

                $date_arr = [];

                $tat = 1;

                $incentive_tat = 1;

                // $vendor_sla_item = DB::table('vendor_slas')->where(['sla_id'=>$task->vendor_sla_id,'service_id'=>$task->service_id])->first();

                // if($vendor_sla_item!=NULL)
                // {
                //     $tat = $vendor_sla_item->tat - 1;

                //     $incentive_tat = $vendor_sla_item->tat - 1;
                // }

                $tat = $task->tat;

                $incentive_tat = $task->tat;

                $date_arr = CommonTrait::workingDays($start_date,$tat,$incentive_tat);

                if(strtotime($end_date) > strtotime($date_arr['tat_date']))
                {
                    $in_out = 2;
                }
                else
                {
                    $in_out = 1;
                }
            }
        }

        return $in_out;
    }

    // User Name from User table
    public static function candidate_user_name($user_id)
    {
        $res_data = "";
        $user_data = DB::table('candidate_reinitiates')
            ->select('name')                
            ->where(['id'=>$user_id])
            ->first();

            if($user_data !=null){
                $res_data = ucfirst($user_data->name);
            }

        return $res_data;
        
    }

    public static function candidate_reference_id($user_id)
    {
        $res_data = "";
        $user_data = DB::table('candidate_reinitiates')
            ->select('display_id')                
            ->where(['id'=>$user_id])
            ->first();

            if($user_data !=null){
                $res_data = ucfirst($user_data->display_id);
            }

        return $res_data;
        
    }

    public static function candidate_details($id)
    {
        $data = NULL;

        $data = DB::table('candidate_reinitiates')->where(['id'=>$id])->first();

        return $data;
    }

    /*
    * @return mix
    */
    public static function get_candidate_fullname($user_id)
    {   $res = '';
        $data = DB::table('candidate_reinitiates')->select('id','first_name','last_name')->where(['id'=>$user_id])->first();
        if($data !=null){
         $res = $data->first_name.' '.$data->last_name;
        }
        return $res;
    }

    public static function getJafRaiseServiceNameSlot($jaf_arr_id)
    {
        $res_data = "";

        $data = DB::table('jaf_form_data as j')
                ->select('j.*','s.name as service_name','s.verification_type')
                ->join('services as s','s.id','=','j.service_id')
                ->whereIn('j.id',$jaf_arr_id)
                ->where('is_insufficiency',1)
                ->get();

        $count=count($data);
        $i=0;

        if(count($data)>0)
        {
            foreach($data as $value)
            {
                $service_name = $value->verification_type=='Manual' ? $value->service_name.' - '.$value->check_item_number : $value->service_name;

                if(++$i==$count)
                {
                    $res_data.= $service_name;
                }
                else
                {
                    $res_data.= $service_name.', ';
                }
            }
        }

        return $res_data;
    }

    public static function jaf_video_links($business_id)
    {
        $res_data = "";
        $links_data = DB::table('jaf_send_video_links')
                    ->select('video_jaf_links')                
                    ->where(['business_id'=>$business_id,'status'=>1])
                    ->first();
        
        if($links_data !=null){
            $res_data = $links_data->video_jaf_links;
        }

        return $res_data;
        
    }
    
}


